<?php

class wadevCreateModelExtCli extends waCliController
{
    public function execute()
    {
        if (!waRequest::param(1) || null !== waRequest::param('help')) {
            return $this->showHelp();
        }

        list($app_id, $class_name, $plugin_name, $table) = $this->getParameters();
        $this->create($app_id, $class_name, $plugin_name, $table);
    }

    protected function showHelp()
    {
        echo <<<HELP
Usage: php wa.php createModelExt app_id/plugin_id db_table_name

    Create an extended model in given app for given mysql table.

Example:
    php wa.php createModel myapp myapp_records

See also:
    php wa.php generateDb --help
HELP;
    }

    protected function create($app_id, $plugin_id, $class_name, $table)
    {
        $files_created = array();

        // Save PHP into a file
        $path = wa()->getAppPath('lib/models/Base/', $app_id);
        if (!file_exists($path)) {
            // shop and helpdesk use `model` dir instead of `models` for some reason
            $path = wa()->getAppPath('lib/models/Base/', $app_id);
        }
        if ($plugin_id) {
            $path = wa()->getAppPath("plugins/{$plugin_id}/lib/models/Base/", $app_id);
        }
        $filename = preg_replace('~Model$~', '', $class_name) . '.model.php';

//        if (file_exists($path . $filename)) {
//            $this->dieWithErrors(array(
//                'File ' . $path . $filename . ' already exists. Please delete or rename it first.',
//            ));
//        }

        waFiles::create($path);
        file_put_contents($path . $filename, $this->getPhp($app_id, $plugin_id, $class_name, $table));
        $files_created[] = $path . $filename;

        print "Successfully created:\n" . join("\n", $files_created);
    }

    protected function getPhp($app_id, $plugin_name, $class_name, $table)
    {
        $result = <<<PHP
<?php

/**
 * Class {$class_name}
%PROPERTY_CONTENT%
 */
class {$class_name} extends wadevModelExt
{
%CLASS_CONTENT%
}
PHP;
        $result = str_replace('%CLASS_CONTENT%', "\tprotected \$table = '{$table}';", $result);
        $result = str_replace('%PROPERTY_CONTENT%', $this->getPhpDocProperties($table), $result);
        $result = str_replace("\t", "    ", $result);
        return $result;
    }

    protected function getPhpDocProperties($table)
    {
        $props = array();
        $model = new waModel();
        $table = $model->describe($table);
        foreach ($table as $property => $item) {
            $props[] = " * @property \${$property} {$this->getType($item['type'])}";
        }
        return implode("\n", $props);
    }

    private function getType($type)
    {
        $types = array(
            'int'        => 'integer',
            'bigint'     => 'integer',
            'smallint'   => 'integer',
            'mediumint'  => 'integer',
            'tinyint'    => 'integer',
            'bool'       => 'boolean',
            'decimal'    => 'float',
            'float'      => 'float',
            'varchar'    => 'string',
            'char'       => 'string',
            'text'       => 'string',
            'mediumtext' => 'string',
            'datetime'   => 'string',
        );
        return array_key_exists($type, $types) ? $types[$type] : 'string';
    }

    protected function getParameters()
    {
        $plugin_id = '';
        $app_id = strtolower(waRequest::param(0));
        if (strpos($app_id, '/')) {
            list($app_id, $plugin_id) = explode('/', $app_id);
        }
        $table = strtolower(waRequest::param(1));
        if (!wa()->appExists($app_id)) {
            $this->dieWithErrors(array(
                'App ' . $app_id . ' does not exist',
            ));
        }
        if ($plugin_id && !wa($app_id)->getPlugin($plugin_id)) {
            $this->dieWithErrors(array(
                'Plugin ' . $plugin_id . ' in App ' . $app_id . ' does not exist',
            ));
        }
        if (!preg_match('~^[a-z][a-z0-9_]*$~', $table)) {
            $this->dieWithErrors(array(
                'Incorrect table name: ' . $table,
            ));
        }

        $table_prefix = $app_id;
//        if ($plugin_id) {
//            $table_prefix .= ('_' . $plugin_id);
//        }
        if ($app_id == 'webasyst') {
            $table_prefix = 'wa';
        }

        if ($app_id != 'webasyst' && !preg_match('~^' . preg_quote($table_prefix, '~') . '_~', $table)) {
            $table = $table_prefix . '_' . $table;
            echo "WARNING: table name must start with an app_id prefix. Going on with '{$table}'.\n";
        }

        $class_name = array($table_prefix);
        foreach (explode('_', preg_replace('~^' . preg_quote($table_prefix, '~') . '_~', '', $table)) as $part) {
            $class_name[] = ucfirst($part);
        }
        $table_real_name = $class_name[0];
        $class_name[0] = 'Base';
        array_unshift($class_name, $table_real_name);
//        if ($plugin_id) {

//        }
        $class_name = implode('', $class_name) . 'Model';
        return array($app_id, $plugin_id, $class_name, $table);
    }

    protected function dieWithErrors($errors)
    {
        print "ERROR:\n";
        print implode("\n", $errors);
        exit;
    }
}

