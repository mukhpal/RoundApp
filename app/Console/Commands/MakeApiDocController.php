<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeApiDocController extends Command
{
    const STUB_APIDOC_NEEDLE = 'APIDOC';
    const DEFAULT_NAMESPACE = 'Http\\Controllers';

    const ACTION_INDEX = 'index';
    const ACTION_SHOW = 'show';
    const ACTION_STORE = 'store';
    const ACTION_UPDATE = 'update';
    const ACTION_DESTROY = 'destroy';

    const STUB_APIDOC = <<<STUB
/**
 * @OA\Tag(
 *     name="{{model.plural}}",
 *     description="API Endpoints of {{model.plural}}"
 * )
{{actions}}
 */
STUB;

    const STUB_APIDOC_ACTIONS = <<<STUB
{{action.index }}{{action.show}}{{action.store}}{{action.update}}{{action.destroy}}
STUB;

    const STUB_APIDOC_ACTION_INDEX = <<<STUB
 * @OA\Get(
 *      path="/{{path}}",
 *      operationId="index{{model.plural}}",
 *      tags={"{{model.plural}}"},
 *      summary="Get {{model.plural}} list",
 *      description="Get {{model.plural}} list",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(type="array",
 *            @OA\Items(ref="#/components/schemas/{{model}}")
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      )
 *  )
STUB;
    const STUB_APIDOC_ACTION_SHOW = <<<STUB
 * @OA\Get(
 *      path="/{{path}}/{id}",
 *      operationId="show{{model}}",
 *      tags={"{{model.plural}}"},
 *      summary="Get {{model}}",
 *      description="Get {{model}}",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/{{model}}"
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      )
 *  )
STUB;
    const STUB_APIDOC_ACTION_STORE = <<<STUB
 * @OA\Post(
 *      path="/{{path}}",
 *      operationId="store{{model.plural}}",
 *      tags={"{{model.plural}}"},
 *      summary="Create {{model}}",
 *      description="Create {{model}}",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/{{model}}"
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      )
 *  )
STUB;
    const STUB_APIDOC_ACTION_UPDATE = <<<STUB
 * @OA\Put(
 *      path="/{{path}}/{id}",
 *      operationId="update{{model.plural}}",
 *      tags={"{{model.plural}}"},
 *      summary="Update {{model}}",
 *      description="Update {{model}}",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/{{model}}"
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      )
 *  )
STUB;
    const STUB_APIDOC_ACTION_DESTROY = <<<STUB
 * @OA\Delete(
 *      path="/{{path}}/{id}",
 *      operationId="delete{{model.plural}}",
 *      tags={"{{model.plural}}"},
 *      summary="Delete {{model}}",
 *      description="Delete {{model}}",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      )
 *  )
STUB;

    const STUB_ACTIONS = [
        self::ACTION_INDEX => self::STUB_APIDOC_ACTION_INDEX,
        self::ACTION_SHOW => self::STUB_APIDOC_ACTION_SHOW,
        self::ACTION_STORE => self::STUB_APIDOC_ACTION_STORE,
        self::ACTION_UPDATE => self::STUB_APIDOC_ACTION_UPDATE,
        self::ACTION_DESTROY => self::STUB_APIDOC_ACTION_DESTROY,
    ];


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:oa-controller {name*} {--model=} {--f|folder} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Open Api Documentation on controller';

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
        $controllers = $this->getControllers();
        foreach($controllers as $controller) {
            $error = "";
            $success = true;
            try{
                $this->make($controller);
            }
            catch (\ErrorException $e) {
                $error = $e->getMessage();
                $success = false;
            }
            if($success)
                $this->info("Controller \"{$controller}\" refreshed successfully!");
            else
                $this->error("Error refreshing controller \"{$controller}\": {$error}");
        }
    }

    public function make($controller)
    {
        //$class = class_exists("App\\" . $controller) ? $controller : static::DEFAULT_NAMESPACE . "\\" . $controller;
        //$class = "App\\" . $controller;
        $class = $controller;
        $force = $this->option('force');
        $className = class_basename($class);
        $model = $this->option('model') ?? basename($className, 'Controller');
        $namespace = basename($class, "\\".$className);
        $classFile = "app/" . Str::of($namespace)->replace("\\", "/") . "/" . $className . ".php";
        throw_unless(file_exists($classFile), new \ErrorException("File \"{$classFile}\" doesnt' exist!"));
        $namespace = "App\\" . $namespace;
        $class = $namespace . "\\" . $className;
        throw_unless(class_exists($class), new \ErrorException("Class \"{$class}\" doesnt' exist!"));
        $re = new \ReflectionClass($class);
        $actionsDoc = [];
        $params = [
            '{{path}}' => (string)Str::of($model)->lower()->plural(),
            '{{namespace}}' => $namespace,
            '{{class}}' => $className,
            '{{model}}' => $model,
            '{{model.plural}}' => Str::plural($model),
        ];
        foreach(static::STUB_ACTIONS as $key => $stub) {
            if($re->hasMethod($key))
                $actionsDoc[] = strtr($stub, $params);
        }
        $params['{{actions}}'] = implode("\n", $actionsDoc);
        $currentCode = file_get_contents($classFile);
        $doc = strtr(static::STUB_APIDOC, $params);
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
    public function getControllers():array
    {
        $c = [];
        $controllers = $this->argument('name');
        $folder = $this->option('folder');
        if($folder) {
            foreach($controllers as $controller) {
                $dir = app_path() . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $controller);
                throw_unless(is_dir($dir), new \ErrorException("\"{$dir}\" is not a directory!"));
                $files = scandir($dir);
                foreach($files as $file) {
                    if(!is_dir($file) && pathinfo($file, PATHINFO_EXTENSION) === "php") {
                        $name = basename($file, ".php");
                        $class = $controller . "\\" . $name;
                        $c[] = $class;
                    }
                }
            }
        }
        else {
            $c = $controllers;
        }
        return $c;
    }
}
