<?php
namespace CoreLib;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Parameters;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\ResourceEvent;
use ZF\Rest\AbstractResourceListener As RestAbstractResourceListener;

abstract class AbstractResourceListener extends RestAbstractResourceListener {
    /**
     * The query params validation config for the resource (must be set by the resource's constructor)
     *
     * @var array
     */
    protected $queryParamsValidation;

    /**
     * An array of validation messages for invalid input
     *
     * @var array
     */
    private $validationMessages = array();

    /**
     * Dispatch an incoming event to the appropriate method
     *
     * Marshals arguments from the event parameters.
     *
     * Also filters and validates query params prior to a fetchAll event. Returns an ApiProblem if validation fails.
     *
     * @param  ResourceEvent $event
     * @return mixed
     */
    public function dispatch(ResourceEvent $event)
    {        
        if ($event->getName() == 'fetchAll' && is_array($this->queryParamsValidation)) {
            try {
                $this->validateQueryParams($event->getQueryParams());
            } catch (\InvalidArgumentException $e) {
                return new ApiProblem(400, 'Failed Validation', null, null, array('validation_messages' => $this->validationMessages));
            }
        }

        return parent::dispatch($event);
    }

    /**
     * Validate the event's query params and retrieve validation messages on invalid input
     *
     * @param Parameters $params
     * @throws \InvalidArgumentException
     */
    private function validateQueryParams(Parameters $params)
    {
        $inputFilter = $this->buildInputFilter($params);

        if ($inputFilter->isValid()) {
            // overwrite parameters with valid values
            $params->fromArray($inputFilter->getValues());
        } else {
            foreach ($inputFilter->getInvalidInput() as $error) {
                $this->validationMessages[$error->getName()] = $error->getMessages();
            }

            throw new \InvalidArgumentException();
        }
    }

    /**
     * Build an input filter for query params validation
     *
     * @param Parameters $params
     * @return InputFilter
     */
    private function buildInputFilter(Parameters $params)
    {
        $inputFilter = new InputFilter();
        $defaultValues = array();

        foreach ($this->queryParamsValidation as $param) {
            $this->assertKeys($param, array('name', 'required', 'default_value', 'filters', 'validators'));

            $defaultValues[$param['name']] = $param['default_value'];

            $input = new Input($param['name']);
            $input->setRequired($param['required']);

            foreach ($param['filters'] as $filter) {
                $this->assertKeys($filter, array('name', 'options'));
                $input->getFilterChain()->attach(new $filter['name']($filter['options']));
            }

            foreach ($param['validators'] as $validator) {
                $this->assertKeys($validator, array('name', 'options'));
                $input->getValidatorChain()->attach(new $validator['name']($validator['options']));
            }

            $inputFilter->add($input);
        }

        $inputFilter->setData(array_merge($defaultValues, (array) $params));

        return $inputFilter;
    }

    /**
     * Assert that needed array config keys are set
     *
     * @param array $array
     * @param array $keys
     * @throws \Exception
     */
    private function assertKeys(array $array, array $keys)
    {
        $missingKeys = array_diff($keys, array_keys($array));

        if ($missingKeys) {
            $missingKeys = join(', ', $missingKeys);
            throw new \Exception("Missing config data [{$missingKeys}] for query params validation");
        }
    }
} 
