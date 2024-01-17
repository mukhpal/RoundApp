<?php


namespace App\Http\Controllers;

use App\Models\MultiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MultiRequestController
{
    public $operations = [
        'operation1' => [
            'steps' => [
                's1' => ['method' => 'step1', 'next' => 's3', 'data' => []],
                's2',
                's3',
            ],
            'commit' => 'commitCallback',
            'rollback' => 'rollbackCallback',
        ]
    ];


    protected function handle(Request $request, string $operation, string $step)
    {
        list($exists, $isFirst, $isLast, $attributes) = $this->stepInfo($operation, $step);
        if(!$exists) {
            abort(401, "Step {$step} doesn't exist.");
        }
        if($isFirst) {
            $multiRequest = MultiRequest::start($operation, $step);
            $uuid = $multiRequest->uuid;
        }
        else {
            if(!$request->hasValidSignature()) {
                abort(401, "Invalid request signature.");
            }
            elseif(!$request->has('uuid')) {
                abort(401, "Please specify a uuid.");
            }
            $uuid = $request->post('uuid');
            $multiRequest = MultiRequest::firstWhere('uuid', $uuid);
            if(!isset($multiRequest)) {
                abort(401, "Please specify a valid uuid.");
            }
            MultiRequest::progress($uuid, $step);
        }
        $method = isset($this->operations[$operation]['steps'][$step]['method']) ?
            $this->operations[$operation]['steps'][$step]['method'] :
            ((string)Str::of("{$operation}_{$step}")->camel());
        if(!method_exists($this, $method)) {
            abort(401, "Method {$method} doesn't exist.");
        }
        $result = $this->call($method, $multiRequest);
        if($isLast) {
            MultiRequest::end($uuid);
        }
    }

    protected function call($method, $multiRequest)
    {
        return $this->$method($multiRequest);
    }

    protected function getSteps(string $operation)
    {
        $steps = [];
        if(isset($this->operations[$operation]['steps'])) {
            foreach ($this->operations[$operation]['steps'] as $k => $v) {
                $key = is_string($k) ? $k : $v['name'];
                $steps[$key] = $v;
            }
        }
        return $steps;
    }

    protected function getStepAttributes(string $operation, string $step)
    {
        $steps = $this->getSteps($operation);
        return isset($steps[$step]) ? $steps[$step] : null;
    }

    protected function getCountSteps(string $operation)
    {
        return count($this->getSteps($operation));
    }

    protected function getStepPosition(string $operation, string $step)
    {
        $steps = array_values($this->getSteps($operation));
        $flip = array_flip($steps);
        return isset($flip[$step]) ? $flip[$step] : -1;
    }

    protected function getNextStep(string $operation, string $step)
    {
        $nextStep = null;
        if(!$this->isLastStep($operation, $step)) {
            $pos = $this->getStepPosition($operation, $step) + 1;
            $nextStep = $this->getSteps($operation)[$pos];
        }
        return $nextStep;
    }

    protected function stepInfo(string $operation, string $step)
    {
        $n = $this->getCountSteps($operation);
        $pos = $this->getStepPosition($operation, $step);
        $attributes = $this->getStepAttributes($operation, $step);
        return [
            'exists' => $pos !== -1,
            'isFirst' => $pos === 0,
            'isLast' => $pos === $n,
            'attributes' => $attributes
        ];
    }

    protected function stepExists(string $operation, string $step)
    {
        return $this->getStepPosition($operation, $step) !== -1;
    }

    protected function isFirstStep(string $operation, string $step)
    {
        return $this->getStepPosition($operation, $step) === 0;
    }

    protected function isLastStep(string $operation, string $step)
    {
        return $this->getStepPosition($operation, $step) === $this->getCountSteps($operation);
    }


    protected function operation1Step1($data)
    {

    }


}
