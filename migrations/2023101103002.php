<?php

return new class{

    public function up()
    {
        echo get_class($this) . " UP FUNCTION";
    }

    public function down(){
        echo get_class($this) . " DOWN FUNCTION";
    }
};