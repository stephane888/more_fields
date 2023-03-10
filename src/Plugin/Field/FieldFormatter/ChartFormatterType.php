<?php

namespace Drupal\more_fields\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'more_fields_chart_formatter_type' formatter.
 *
 * @FieldFormatter(
 *   id = "more_fields_chart_formatter_type",
 *   label = @Translation("Chart formatter type"),
 *   field_types = {
 *     "more_fields_chart_field_type"
 *   }
 * )
 */
class ChartFormatterType extends FormatterBase {
  
  /**
   *
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'layoutgenentitystyles_view' => 'more_fields/field-chart'
    ] + parent::defaultSettings();
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // utilile pour mettre à jour le style
      'layoutgenentitystyles_view' => [
        '#type' => 'hidden',
        '#value' => 'more_fields/field-chart'
      ]
    ] + parent::settingsForm($form, $form_state);
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.
    
    return $summary;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [
      '#theme' => 'more_fields_field_chart',
      '#attached' => [
        'library' => [
          'more_fields/field_chart'
        ]
      ]
    ];
    $labels = [];
    $datas = [];
    $backgroundColor = [];
    foreach ($items as $item) {
      $labels[] = $item->label;
      $datas[] = $item->value;
      $backgroundColor[] = "rgba(" . $this->hex2rgb($item->color) . ",0.7)";
      // $backgroundColor[] = $item->color;
    }
    $elements['#attached']['drupalSettings']['more_fields']['chart_config'] = [
      'type' => 'polarArea',
      'data' => [
        'labels' => $labels,
        'datasets' => [
          [
            'label' => '',
            'data' => $datas,
            'backgroundColor' => $backgroundColor
          ]
        ]
      ]
    ];
    return $elements;
  }
  
  protected function hex2rgb($sTrimmedString) {
    $sTrimmedString = trim($sTrimmedString, '#');
    if (strlen($sTrimmedString) === 3) {
      list($iRed, $iGreen, $iBlue) = array_map(function ($sColor) {
        return hexdec(str_repeat($sColor, 2));
      }, str_split($sTrimmedString, 1));
    }
    else
      list($iRed, $iGreen, $iBlue) = array_map('hexdec', str_split($sTrimmedString, 2));
    return $iRed . ',' . $iGreen . ',' . $iBlue;
  }
  
}
