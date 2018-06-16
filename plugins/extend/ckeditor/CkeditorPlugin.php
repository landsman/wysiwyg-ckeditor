<?php

namespace SunlightExtend\Ckeditor;

use Sunlight\Core;
use Sunlight\Plugin\Action\ConfigAction;
use Sunlight\Plugin\ExtendPlugin;

/**
 * CKEditor plugin
 *
 * @author Jirka Daněk <jdanek.eu>
 */
class CkeditorPlugin extends ExtendPlugin
{
    private $wysiwygDetected = false;

    protected function getConfigDefaults()
    {
        return array(
            'editor_mode' => 'basic',
        );
    }

    /**
     * @param array $args
     */
    public function onHead(array $args)
    {
        if (_logged_in && !$this->isDisabled() && !$this->wysiwygDetected && (bool)Core::$userData['wysiwyg'] === true) {
            $args['js'][] = $this->getWebPath() . '/Resources/ckeditor/ckeditor.js';
            $args['js'][] = $this->getWebPath() . '/Resources/wysiwyg_' . $this->getConfig()->offsetGet('editor_mode') . '.js';
        }
    }

    /**
     * @param $args
     */
    public function onWysiwyg($args)
    {
        if ($args['available']) {
            $this->wysiwygDetected = true;
        } elseif (_logged_in && !$this->isDisabled() && (bool)Core::$userData['wysiwyg']) {
            $args['available'] = true;
        }
    }

    /**
     * @param array $args
     */
    public function onCoreJavascript(array $args)
    {
        $args['variables']['pluginWysiwyg'] = array(
            'systemLang' => _language,
        );
    }

    public function getAction($name)
    {
        if ($name == 'config') {
            return new CustomConfig($this);
        }
        return parent::getAction($name);
    }
}

class CustomConfig extends ConfigAction
{
    protected function getFields()
    {
        $modes = array(
            _lang('wysiwyg.limited') => 'limited',
            _lang('wysiwyg.basic') => 'basic',
            _lang('wysiwyg.advanced') => 'advanced'
        );

        return array(
            'editor_mode' => array('label' => _lang('wysiwyg.mode'), 'input' => $this->createSelect('editor_mode', $modes, $this->plugin->getConfig()->offsetGet('editor_mode')), 'type' => 'text'),
        );
    }

    private function createSelect($name, $options, $default)
    {
        $result = "<select name='config[" . $name . "]'>";
        foreach ($options as $k => $v) {
            $result .= "<option value='" . $v . "'" . ($default == $v ? " selected" : "") . ">" . $k . "</option>";
        }
        $result .= "</select>";
        return $result;
    }
}
