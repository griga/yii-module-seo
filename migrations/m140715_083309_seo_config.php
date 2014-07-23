<?php

class m140715_083309_seo_config extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{seo_config}}', [
            'id' => 'pk',
            'key' => 'VARCHAR(255) NOT NULL',
            'value' => 'VARCHAR(255) NULL DEFAULT NULL',
        ]);

        $this->createIndex('sc_ku', '{{seo_config}}', 'key', true);
        $this->createTable('{{seo_config_lang}}',[
            'l_id' => 'pk',
            'entity_id' => 'INT NOT NULL',
            'lang_id' => 'VARCHAR(6) NOT NULL',
            'l_value' => 'VARCHAR(255) ',
        ]);
        $this->createIndex('sc_ei', '{{seo_config_lang}}', 'entity_id');
        $this->createIndex('sc_li', '{{seo_config_lang}}', 'lang_id');

        $this->addForeignKey('sc_ibfk_1', '{{seo_config_lang}}', 'entity_id', '{{seo_config}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable('{{seo_config_lang}}');
        $this->dropTable('{{seo_config}}');

    }

}