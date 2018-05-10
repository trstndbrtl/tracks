<?php

namespace Drupal\Tests\tracks\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests the CSV Track Importer
 *
 * @group tracks
 */
class CsvImporterTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['system', 'csv_importer_test', 'tracks', 'image', 'file', 'user'];

  /**
   * Tests the import of the CSV based plugin.
   */
  public function testImport() {
    $this->installEntitySchema('track');
    $this->installEntitySchema('file');
    $this->installSchema('file', 'file_usage');
    $manager = $this->container->get('entity_type.manager');
    $tracks = $manager->getStorage('track')->loadMultiple();
    $this->assertEmpty($tracks);

    $csv_path = drupal_get_path('module', 'csv_importer_test') . '/tracks.csv';
    $csv_contents = file_get_contents($csv_path);
    $file = file_save_data($csv_contents, 'public://simpletest-tracks.csv', FILE_EXISTS_REPLACE);
    $config = $manager->getStorage('importer')->create([
      'id' => 'csv',
      'label' => 'CSV',
      'plugin' => 'csv',
      'plugin_configuration' => [
        'file' => [$file->id()]
      ],
      'source' => 'Testing',
      'bundle' => 'goods',
      'update_existing' => true
    ]);
    $config->save();

    $plugin = $this->container->get('tracks.importer_manager')->createInstanceFromConfig('csv');
    $plugin->import();
    $tracks = $manager->getStorage('track')->loadMultiple();
    $this->assertCount(2, $tracks);

    $tracks = $manager->getStorage('track')->loadByProperties(['number' => 45345]);
    $this->assertNotEmpty($tracks);
    $this->assertCount(1, $tracks);
  }
}