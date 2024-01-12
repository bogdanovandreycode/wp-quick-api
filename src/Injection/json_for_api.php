<?php

/**
 * @Copyright ((c) plasma-web.ru
 * @license    GPLv2 or later
 */

namespace QuickForm;

\defined('QF3_VERSION') or die;

class qfEmail_tmpl extends qfEmail
{
    public function getTmpl($project, $data, $calculator)
    {
        $html = '';
        if (!$project->params->showtitle) {
            $html .= '<h3>' . $project->title . '</h3>';
        }

        if ($project->params->showurl) {
            $html .= $this->checkUrl();
        }

        if ($project->params->calculatortype) {
            $data['sum'] = $calculator;
        }

        $data_without_html = array();

        foreach ($data as $field) {
            if ($field->teg != 'customHtml') {
                $field->value = wp_strip_all_tags($field->value); // удалить все управляющие и расширенные ASCII-символы
                $field->value = str_replace("\"", "'", $field->value); // заменить одинарные кавычки на пробелы
                $field->value = str_replace(array("\r", "\n"), " ", $field->value); // заменить переходы на новую строку на пробелы
                array_push($data_without_html, ['name' => $field->label, 'value' => $field->value]);
            }
        }

        $data = $data_without_html;

        $html .= json_encode($data, JSON_UNESCAPED_UNICODE);
        return $html;
    }
}
