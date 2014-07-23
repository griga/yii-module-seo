<?php

class m140708_124609_seo extends DbMigration
{
    public function up()
    {
        $this->createTable('{{seo}}', [
            'id' => 'pk',
            'key' => 'VARCHAR(255) NOT NULL',
            'title' => 'VARCHAR(255) NULL DEFAULT NULL',
            'description' => 'VARCHAR(255) NULL DEFAULT NULL',
            'keywords' => 'VARCHAR(255) NULL DEFAULT NULL',
            'meta' => 'TEXT NULL DEFAULT NULL',
        ]);

        $this->createIndex('s_ku', '{{seo}}', 'key', true);
        $this->createTable('{{seo_lang}}',[
            'l_id' => 'pk',
            'entity_id' => 'INT NOT NULL',
            'lang_id' => 'VARCHAR(6) NOT NULL',
            'l_title' => 'VARCHAR(255) ',
            'l_description' => 'VARCHAR(255) ',
            'l_keywords' => 'VARCHAR(255) ',
        ]);
        $this->createIndex('s_ei', '{{seo_lang}}', 'entity_id');
        $this->createIndex('s_li', '{{seo_lang}}', 'lang_id');

        $this->addForeignKey('s_ibfk_1', '{{seo_lang}}', 'entity_id', '{{seo}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable('{{seo_lang}}');
        $this->dropTable('{{seo}}');

    }

}