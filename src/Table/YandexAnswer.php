<?php

namespace quickapi\Table;

class YandexAnswer
{
    public static function createTable()
    {
        global $wpdb;
        $tableName = self::getTableName();
        $charsetCollate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $tableName (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            form_id varchar(255) NOT NULL,
            integration_id mediumint(9) NOT NULL,
            json_fields text NOT NULL,
            create_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charsetCollate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function deleteTable()
    {
        global $wpdb;
        $tableName = self::getTableName();
        $sql = "DROP TABLE IF EXISTS $tableName;";
        $wpdb->query($sql);
    }

    public static function getTableName()
    {
        global $wpdb;
        return $wpdb->prefix . 'yandex_integrations';
    }

    public static function get(int $id)
    {
        global $wpdb;
        $tableName = self::getTableName();
        $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE id = %d", $id);
        return $wpdb->get_row($sql);
    }

    public static function getAll()
    {
        global $wpdb;
        $tableName = self::getTableName();
        $sql = "SELECT * FROM $tableName";
        return $wpdb->get_results($sql);
    }

    public static function getByFormId(int $formId)
    {
        global $wpdb;
        $tableName = self::getTableName();
        $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE form_id = %s", $formId);
        return $wpdb->get_results($sql);
    }

    public static function getByIntegrationId(int $integrationId)
    {
        global $wpdb;
        $tableName = self::getTableName();
        $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE integration_id = %d", $integrationId);
        return $wpdb->get_results($sql);
    }

    public static function addIntegration($formId, $integrationId, $jsonFields)
    {
        global $wpdb;
        $tableName = self::getTableName();
        $wpdb->insert($tableName, [
            'form_id' => $formId,
            'integration_id' => $integrationId,
            'json_fields' => $jsonFields
        ]);
        return $wpdb->insert_id;
    }

    public static function deleteIntegration($id)
    {
        global $wpdb;
        $tableName = self::getTableName();
        $wpdb->delete($tableName, ['id' => $id]);
    }

    public static function deleteIntegrationByFormId($formId)
    {
        global $wpdb;
        $tableName = self::getTableName();
        $wpdb->delete($tableName, ['form_id' => $formId]);
    }
}
