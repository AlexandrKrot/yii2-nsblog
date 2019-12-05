<?php

use yii\db\Migration;

/**
 * Class m191128_054256_create_blog_tables
 */
class m191128_054256_create_blog_tables extends Migration
{
    const PUBLISH     = 1;
    const ACCESS_READ = 1;
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createCategory();
        $this->createPage();
        $this->createRelations();
        
        $this->fillData();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%related_page}}');
        $this->dropTable('{{%related_category}}');
        $this->dropTable('{{%additional_page}}');
        $this->dropTable('{{%additional_category}}');
        
        $this->dropTable('{{%meta_blog_category}}');
        $this->dropTable('{{%meta_blog_page}}');
        $this->dropTable('{{%page}}');
        $this->dropTable('{{%category}}');
        return true;
    }
    
    private function createCategory()
    {
        $this->createTable('{{%category}}', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(255)->notNull(),
            'url'        => $this->string(255)->notNull(),
            'author_id'  => $this->integer()->notNull(),
            'status'     => $this->integer(2)->notNull(),
            'h1'         => $this->string(255)->notNull(),
            'image'      => $this->string(255)->notNull(),
            'preview_text' => $this->text(),
            'full_text'    => $this->text(),
            'tree'       => $this->integer(),
            'lft'        => $this->integer()->notNull(),
            'rgt'        => $this->integer()->notNull(),
            'depth'      => $this->integer()->notNull(),
            'position'   => $this->integer()->notNull()->defaultValue(0),
            'access_read'   => $this->integer()->notNull(),
            'domain_id'  => $this->integer(),
            'lang_id'    => $this->integer(),
            'publish_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-category-domain_id', '{{%category}}', 'domain_id', '{{%domain}}', 'id');
        $this->addForeignKey('fk-category-lang_id', '{{%category}}', 'lang_id', '{{%language}}', 'id');
        
        $this->createTable('{{%meta_blog_category}}', [
            'id'         => $this->primaryKey(),
            'src_id'     => $this->integer()->notNull(),
            'title' => $this->string(255),
            'keywords' => $this->string(255),
            'description' => $this->text(),
            'og:title'    => $this->string(255),
            'og:description' => $this->text(),
            'domain_id'  => $this->integer(),
            'lang_id'    => $this->integer(),
        ]);
        
        $this->addForeignKey('fk-meta_blog_category-src_id', '{{%meta_blog_category}}', 'src_id', '{{%category}}', 'id');
        $this->addForeignKey('fk-meta_blog_category-domain_id', '{{%meta_blog_category}}', 'domain_id', '{{%domain}}', 'id');
        $this->addForeignKey('fk-meta_blog_category-lang_id', '{{%meta_blog_category}}', 'lang_id', '{{%language}}', 'id');
        
    }
    
    private function createPage()
    {
        $this->createTable('{{%page}}', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string()->notNull(),
            'url'        => $this->string(255)->notNull(),
            'author_id'  => $this->integer()->notNull(),
            'image'      => $this->string(255)->notNull(),
            'preview_text' => $this->text(),
            'full_text'    => $this->text(),
            'position'   => $this->integer()->notNull()->defaultValue(0),
            'domain_id'  => $this->integer(),
            'lang_id'    => $this->integer(),
            'publish_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-page-domain_id', '{{%page}}', 'domain_id', '{{%domain}}', 'id');
        $this->addForeignKey('fk-page-lang_id', '{{%page}}', 'lang_id', '{{%language}}', 'id');
        
        $this->createTable('{{%meta_blog_page}}', [
            'id'         => $this->primaryKey(),
            'src_id'     => $this->integer()->notNull(),
            'title' => $this->string(255),
            'keywords' => $this->string(255),
            'description' => $this->text(),
            'og:title'    => $this->string(255),
            'og:description' => $this->text(),
            'domain_id'  => $this->integer(),
            'lang_id'    => $this->integer(),
        ]);
        
        $this->addForeignKey('fk-meta_blog_page-src_id', '{{%meta_blog_page}}', 'src_id', '{{%page}}', 'id');
        $this->addForeignKey('fk-meta_blog_page-domain_id', '{{%meta_blog_page}}', 'domain_id', '{{%domain}}', 'id');
        $this->addForeignKey('fk-meta_blog_page-lang_id', '{{%meta_blog_page}}', 'lang_id', '{{%language}}', 'id');
    }
    
    private function createRelations()
    {
        $this->createTable('{{%additional_category}}', [
            'id'         => $this->primaryKey(),
            'parent_id'  => $this->integer()->notNull(),
            'child_id'   => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-additional_category-parent_id', '{{%additional_category}}', 'parent_id', '{{%category}}', 'id');
        $this->addForeignKey('fk-additional_category-child_id', '{{%additional_category}}', 'child_id', '{{%category}}', 'id');
        
        $this->createTable('{{%additional_page}}', [
            'id'         => $this->primaryKey(),
            'parent_id'  => $this->integer()->notNull(),
            'child_id'   => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-additional_page-parent_id', '{{%additional_page}}', 'parent_id', '{{%category}}', 'id');
        $this->addForeignKey('fk-additional_page-child_id', '{{%additional_page}}', 'child_id', '{{%page}}', 'id');
        
        $this->createTable('{{%related_category}}', [
            'id'         => $this->primaryKey(),
            'parent_id'  => $this->integer()->notNull(),
            'child_id'   => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-related_category-parent_id', '{{%related_category}}', 'parent_id', '{{%category}}', 'id');
        $this->addForeignKey('fk-related_category-child_id', '{{%related_category}}', 'child_id', '{{%category}}', 'id');
        
        $this->createTable('{{%related_page}}', [
            'id'         => $this->primaryKey(),
            'parent_id'  => $this->integer()->notNull(),
            'child_id'   => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk-related_page-parent_id', '{{%additional_page}}', 'parent_id', '{{%category}}', 'id');
        $this->addForeignKey('fk-related_page-child_id', '{{%additional_page}}', 'child_id', '{{%page}}', 'id');
    }
    
    private function fillData()
    {
        $time = time();
        
        $this->insert('{{%category}}', [
            'name'        => 'Tree Categories',
            'url'         => '',
            'author_id'   => 0,
            'status'      => self::PUBLISH,
            'h1'          => 'Tree Categories',
            'image'       => '',
            'lft'         => 1,
            'rgt'         => 2,
            'depth'       => 0,
            'access_read' => self::ACCESS_READ,
            'publish_at'  => $time,
            'created_at'  => $time,
            'updated_at'  => $time
        ]);
    }
}
