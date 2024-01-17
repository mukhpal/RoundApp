<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MakeApiDocModel extends Command
{
    const STUB_APIDOC = <<<STUB
/**
 * Class {{ class }}
 *
 * @OA\Schema(
 *   description="Entity {{ class }}",
 *   required={
 *       {{ requiredProperties }}
 *   },
 * )
 *
{{ propertiesSchema }}
 */
STUB;

    const STUB_APIDOC_NEEDLE = 'APIDOC';

    const TYPE_INTEGER = 'integer';
    const TYPE_NUMBER = 'number';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_STRING = 'string';
    const TYPE_OBJECT = 'object';
    const TYPE_JSON = 'json';

    private $_mapTypes;

    protected static $types = [
        self::TYPE_INTEGER => ['int', 'bigint', 'smallint', 'integer'],
        self::TYPE_NUMBER => ['float', 'double', 'decimal'],
        self::TYPE_BOOLEAN => ['bool', 'boolean'],
        self::TYPE_STRING => ['string', 'varchar', 'text', 'datetime'],
        self::TYPE_JSON => ['json', 'jsonb'],
        //self::TYPE_OBJECT => ['json', 'jsonb'],
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:oa-model {models*} {--table=} {--f|folder} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $models = $this->getModels();
        foreach ($models as $model) {
            $error = "";
            $success = true;
            try{
                $params = $this->getParams();
                $this->make($model, $params);
            }
            catch (\ErrorException $e) {
                $error = $e->getMessage();
                $success = false;
            }
            if($success)
                $this->info("Model \"{$model}\" refreshed successfully!");
            else
                $this->error("Error refreshing model \"{$model}\": {$error}");
        }
    }

    /**
     * @param $model
     * @param array $params
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function make($model, array $params)
    {



        /* @var \Doctrine\DBAL\Schema\AbstractSchemaManager $doctrineSchema */
        /* @var \Doctrine\DBAL\Schema\Column[] $columns */
        $doctrineSchema = DB::connection()->getDoctrineSchemaManager();
        $table = $this->option('table');
        $force = $this->option('force');
        $className = class_basename($model);
        $namespace = basename($model, "\\".$className);
        $table = isset($table) ? $table : (string)Str::of($className)->snake()->plural();
        throw_unless(Schema::hasTable($table), new \ErrorException("Table {$table} doesn't exist!"));
        $classFile = "app/" . Str::of($namespace)->replace("\\", "/") . "/" . $className . ".php";
        throw_unless(file_exists($classFile), new \ErrorException("File \"{$classFile}\" doesnt' exist!"));

        $namespace = "App\\" . $namespace;
        $class = $namespace . "\\" . $className;
        //$re = new \ReflectionClass($class);
        //$methods = $re->getMethods(\ReflectionMethod::IS_PUBLIC);

        $columns = $doctrineSchema->listTableColumns($table);
        $requiredProperties = [];
        $propertiesSchema = [];
        foreach ($columns as $key => $column) {
            $name = str_replace("\"", "", $key);
            $info = $this->getColumnInfo($column);
            $type = $info['type'];
            $format = $info['format'];
            $length = $info['length'];
            //$precision = $info['precision'];

            $properties = [
                " *   property=\"{$name}\",",
                " *   type=\"{$type}\",",
                " *   format=\"{$format}\",",
                " *   description=\"{$name}\",",
            ];
            if($length) {
                $properties[] = " *   maxLength={$length}";
            }
            $p = implode("\n", $properties);
            $propertiesSchema[] = <<<TPL
 * @OA\Property(
{$p}
 * )
TPL;
            if($info['required'])
                $requiredProperties[] = "\"{$name}\"";
        }

        /* @var \Doctrine\DBAL\Schema\ForeignKeyConstraint[] $fkConstraints */
        $foreignKeyConstraints = $doctrineSchema->listTableForeignKeys($table);

        if($foreignKeyConstraints) {
            $propertiesSchema[] = "";
            foreach($foreignKeyConstraints as $foreignKeyConstraint) {
                $fkColumns = $foreignKeyConstraint->getColumns();
                $refTable = $foreignKeyConstraint->getForeignTableName();
                $refColumns = $foreignKeyConstraint->getForeignColumns();
                $fkColumn = basename($fkColumns[0], "_id");
                $relation = (string)Str::of($fkColumn)->camel();
                $refEntity = (string)Str::of($refTable)->singular()->camel()->ucfirst();
                $modelClass = "\\App\\Models\\{$refEntity}";
                $type = "object";
                $properties = [
                    " *   property=\"{$relation}\",",
                    " *   type=\"{$type}\",",
                    " *   @OA\Items(ref=\"#/components/schemas/{$refEntity}\"),",
                    " *   description=\"{$refEntity}\"",
                ];
                $p = implode("\n", $properties);
                $propertiesSchema[] = <<<TPL
 * @OA\Property(
{$p}
 * )
TPL;
            }
        }
        $params = [
            'namespace' => $namespace,
            'class' => $className,
            'requiredProperties' => implode(", ", $requiredProperties),
            'propertiesSchema' => implode("\n", $propertiesSchema),
        ];
        $p = [];
        array_walk($params, function($v, $k) use(&$p) {
            $p["{{ {$k} }}"] = $v;
        });
        throw_unless(class_exists($class), new \ErrorException("Class \"{$class}\" doesnt' exist!"));
        $currentCode = file_get_contents($classFile);
        $doc = strtr(static::STUB_APIDOC, $p);
        $re = new \ReflectionClass($class);
        $currentDoc = $re->getDocComment();
        $code = "";
        if(!$currentDoc) {
            // Api Documentation is absent
            $needle = "/*" . static::STUB_APIDOC_NEEDLE . "*/";
            if(strpos($currentCode, $needle)) {
                // Needle found
                $code = str_replace($needle, $doc, $currentCode);
            }
            else {
                $this->warn("Needle {$needle} not found.");
            }
        }
        elseif(!strpos($currentCode, $doc)) {
            // Api Documentation changed
            if($force) {
                $this->info("Api Documentation changed.");
                $code = str_replace($currentDoc, $doc, $currentCode);
            }
            else {
                $this->warn("Api Documentation is changed. Force command with --force option to change file.");
            }
        }
        else {
            // Api Documentation already exists and is unchanged
            $this->warn("Api Documentation is unchanged.");
        }
        if(!empty($code)) {
            throw_unless(file_put_contents($classFile, $code), new \ErrorException("Unable to save file {$classFile}"));
            $this->info("File {$classFile} modified successfully.");
        }
        else {
            $this->warn("File not modified.");
        }
    }

    /**
     * @return array
     * @throws \Throwable
     */
    protected function getModels():array
    {
        $m = [];
        $models = $this->argument('models');
        $folder = $this->option('folder');
        if($folder) {
            foreach($models as $model) {
                $dir = app_path() . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $model);
                throw_unless(is_dir($dir), new \ErrorException("\"{$dir}\" is not a directory!"));
                $files = scandir($dir);
                foreach($files as $file) {
                    if(!is_dir($file) && pathinfo($file, PATHINFO_EXTENSION) === "php") {
                        $name = basename($file, ".php");
                        $class = $model . "\\" . $name;
                        $m[] = $class;
                    }
                }
            }
        }
        else {
            $m = $models;
        }
        return $m;
    }

    protected function getParams():array
    {
        return [

            'properties' => [],
            'relations' => [],
            'required' => [],
            'fillable' => []
        ];
    }

    /**
     * @param \Doctrine\DBAL\Schema\Column $column
     * @return array
     */
    protected function getColumnInfo(\Doctrine\DBAL\Schema\Column $column):array
    {
        $type = $this->getColumnType($column, $format);
        return [
            'name' => $column->getName(),
            'type' => $type,
            'format' => $format,
            'length' => $column->getLength(),
            'precision' => $column->getPrecision(),
            'required' => $column->getNotnull()
        ];
    }

    /**
     * @param \Doctrine\DBAL\Schema\Column $column
     * @param string|null $format
     * @return string
     */
    protected function getColumnType(\Doctrine\DBAL\Schema\Column $column, string &$format = null):string
    {
        $t = [];
        $columnName = $column->getName();
        $dbType = $column->getType()->getName();
        if(!isset($this->_mapTypes)) {
            foreach(static::$types as $type => $dbTypes) {
                $keys = $dbTypes;
                $values = array_fill(0, count($keys), $type);
                $t = array_replace_recursive($t, array_combine($keys, $values));
            }
            $this->_mapTypes = $t;
        }
        if(key_exists($dbType, $this->_mapTypes)) {
            $type = $this->_mapTypes[$dbType];
            $format = $dbType;
            if($type == static::TYPE_STRING) {
                if($dbType == 'date')
                    $format = 'date';
                elseif($dbType == 'datetime')
                    $format = 'date-time';
                elseif(Str::contains($columnName, 'email'))
                    $format = 'email';
                elseif(Str::contains($columnName, 'password'))
                    $format = 'password';
            }
        }
        else {
            $type = 'unknown';
            $format = $dbType;
        }
        return $type;
    }
}
