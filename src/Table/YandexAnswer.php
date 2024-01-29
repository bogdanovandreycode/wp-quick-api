<?php

namespace quickapi\Table;

class YandexAnswer
{
    public static function createTable(): array
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
        return dbDelta($sql);
    }

    public static function deleteTable(): bool|int
    {
        global $wpdb;
        $tableName = self::getTableName();
        $sql = "DROP TABLE IF EXISTS $tableName;";
        return $wpdb->query($sql);
    }

    public static function getTableName(): string
    {
        global $wpdb;
        return $wpdb->prefix . 'yandex_integrations';
    }

    public static function get(int $id): array|object|null
    {
        global $wpdb;
        $tableName = self::getTableName();
        $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE id = %d", $id);
        return $wpdb->get_row($sql);
    }

    public static function getAll(): array|object|null
    {
        global $wpdb;
        $tableName = self::getTableName();
        $sql = "SELECT * FROM $tableName";
        return $wpdb->get_results($sql);
    }

    public static function getByFormId(string $formId): array|object|null
    {
        global $wpdb;
        $tableName = self::getTableName();
        $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE form_id = %s", $formId);
        return $wpdb->get_results($sql);
    }

    public static function getByIntegrationId(int $integrationId): array|object|null
    {
        global $wpdb;
        $tableName = self::getTableName();
        $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE integration_id = %d", $integrationId);
        return $wpdb->get_results($sql);
    }

    public static function add(string $formId, int $integrationId, string $jsonFields): int
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

    public static function deleteIntegration(int $id): bool|int
    {
        global $wpdb;
        $tableName = self::getTableName();
        return $wpdb->delete($tableName, ['id' => $id]);
    }

    public static function deleteIntegrationByFormId(string $formId): bool|int
    {
        global $wpdb;
        $tableName = self::getTableName();
        return $wpdb->delete($tableName, ['form_id' => $formId]);
    }

    public static function getHistoryByDateAndLast(int $integrationId, string $formId, string $date, int $lastId): array|object|null
    {
        global $wpdb;
        $tableName = self::getTableName();

        $sql = $wpdb->prepare(
            "SELECT `id`, `json_fields`, `create_date` FROM `$tableName` WHERE `integration_id` = %d AND `form_id` = %s AND `create_date` > %s AND `id` > %d",
            $integrationId,
            $formId,
            $date,
            $lastId
        );

        return $wpdb->get_results($sql, ARRAY_A);
    }
}
