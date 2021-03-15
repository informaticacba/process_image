<?php 

namespace Drupal\ngt_general;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * 
 * 
 */
class CardBlockBase extends BlockBase implements CardBlockBaseInterface {
    

    protected $build = [];
    protected $class = 'block-white';
    protected $config_name = 'ngtBlock';
    protected $directive = '';
    protected $uuid = NULL;
    protected $dataAngular;
    
    /**
     * cardBuildVarBuild
     *
     * @param  mixed $parameters
     * @param  mixed $others
     * @return void
     */
    public function cardBuildVarBuild($parameters = [], $others = []){
        $build = [
            '#theme' => $parameters['theme'],
            '#uuid' => $this->uuid,
            '#directive' => $this->directive,
            '#class' => $this->class,
            '#plugin_id' => $this->getPluginId(),
        ];

        if(isset($parameters['library'])){
            $build['#attached'] = [
                'library' => [
                    $parameters['library'],
                ],
            ];
        }

        if(!empty($others)){
            foreach ($others as $key => $value) {
                $build[$key] = $value;
            }
        }

        $build['#cache']['max-age'] = 0;
        $this->build = $build;
    }
    
    /**
     * cardBuildConfigBlock
     *
     * @param  mixed $endPoint
     * @param  mixed $others
     * @return void
     */
    public function cardBuildConfigBlock($endPoint = NULL, $others = []){
        $config_block = [];
        $other_config = [
            'url' => $endPoint,
            'uuid' => $this->uuid,
            'config_name' => $this->config_name,
        ];

        if(!empty($others)){
            foreach ($others as $key => $value) {
                $config_block[$key] = $value;
            }
        }
        return $config_block;
    }
    
    /**
     * cardBuilAddConfigDirective
     *
     * @param  mixed $config
     * @param  mixed $name
     * @param  mixed $others
     * @return void
     */
    public function cardBuildAddConfigDirective($config = [], $name = NULL, $others = []){
        if(isset($name)){
            $this->build['#attached']['drupalSettings'][$name][$this->uuid] = $config;
        }else{
            $this->build['#attached']['drupalSettings']['ngtBlock'][$this->uuid] = $config;
        }

        if(!empty($others)){
            foreach ($others as $key => $value) {
                $this->build['#attached']['drupalSettings'][$key][$this->uuid] = $value;
            }
        }
    }
    
    /**
     * getValue
     * 
     * Get value of the field passed as a parameter
     *
     * @param  string $field
     * @return void
     */
    public function getValue($field){
        return $this->$field;
    }
    
    /**
     * setValue
     * 
     * Get value of the field passed as a parameter
     * 
     * @param  mixed $field
     * @param  mixed $value
     * @return void
     */
    public function setValue($field, $value){
        return $this->$field = $value;
    }

    public function build(){
        return parent::build();
    }

    public function getCacheMaxAge(){
        return 0;
    }
    
    /**
     * uuid
     *
     * @param  string $name
     * @return hash
     */
    public function uuid($name){
        $hash = $this->uuid = md5($name);
        return $hash;
    }
}