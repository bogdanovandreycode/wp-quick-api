<?php

/**
 * @package Wp quick Api
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

namespace quickapi;

class DataBase
{
    private static $wpdb;

    public static function init()
    {
        global $wpdb;
        self::$wpdb = $wpdb;
    }

    public static function getProjects()
    {
        return self::$wpdb->get_results(
            "SELECT * FROM `wp_qf3_projects`",
            ARRAY_A
        );
    }

    public static function getProject($id)
    {
        return self::$wpdb->get_row(
            self::$wpdb->prepare("SELECT * FROM `wp_qf3_projects` WHERE `id` = %d", $id),
            ARRAY_A
        );
    }

    public static function getHistory($project_id)
    {
        return self::$wpdb->get_results(
            self::$wpdb->prepare("SELECT `id`, `st_form`, `st_date` FROM `wp_qf3_ps` WHERE `st_formid` = %d", $project_id),
            ARRAY_A
        );
    }

    public static function getHistoryByDate($project_id, $date)
    {
        return self::$wpdb->get_results(
            self::$wpdb->prepare(
                "SELECT `id`, `st_form`, `st_date` FROM `wp_qf3_ps` WHERE `st_formid` = %d AND `st_date` > %s",
                $project_id,
                $date
            ),
            ARRAY_A
        );
    }

    public static function getHistoryByDateAndLast($project_id, $date, $last_id)
    {
        return self::$wpdb->get_results(
            self::$wpdb->prepare(
                "SELECT `id`, `st_form`, `st_date` FROM `wp_qf3_ps` WHERE `st_formid` = %d AND `st_date` > %s AND `id` > %d",
                $project_id,
                $date,
                $last_id
            ),
            ARRAY_A
        );
    }

    public static function getForms($project_id)
    {
        return self::$wpdb->get_results(
            self::$wpdb->prepare(
                "SELECT `id`, `fields` FROM `wp_qf3_forms` WHERE `projectid` = %d",
                $project_id
            ),
            ARRAY_A
        );
    }

    public static function updateForm($form_id, $fields)
    {
        return $result = self::$wpdb->update(
            'wp_qf3_forms', // имя таблицы
            array('fields' => $fields), // массив данных для обновления
            array('id' => $form_id), // массив условий для выбора строк для обновления
            array('%s'), // типы данных для обновляемых полей
            array('%d') // типы данных для условий
        );
    }
}
