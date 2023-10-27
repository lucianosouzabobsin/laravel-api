<?php
namespace Tests\Feature\resources\Module;

class ModuleData
{
    public function getDataAdd()
    {
        return [
            'name' => 'Module 1',
            'description' => 'Module teste um',
            'active' => '1'
        ];
    }

    public function getDataUpdate()
    {
        return [
            'id' => 1,
            'name' => 'Module 1',
            'description' => 'Module teste um',
            'active' => '1'
        ];
    }
}