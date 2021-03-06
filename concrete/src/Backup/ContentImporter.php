<?php
namespace Concrete\Core\Backup;

use Concrete\Core\File\Importer;
use Concrete\Core\Page\Type\Composer\FormLayoutSetControl;
use Core;

class ContentImporter
{

    protected static $mcBlockIDs = array();
    protected static $ptComposerOutputControlIDs = array();

    public function importContentFile($file)
    {
        $sx = simplexml_load_file($file);
        $this->import($sx);
    }

    public function importContentString($string)
    {
        $sx = simplexml_load_string($string);
        $this->import($sx);
    }

    protected function import(\SimpleXMLElement $element)
    {
        $manager = Core::make('import/item/manager');
        foreach ($manager->getImporterRoutines() as $routine) {
            $routine->import($element);
        }
    }

    public static function getMasterCollectionTemporaryBlockIDs()
    {
        return self::$mcBlockIDs;
    }

    public static function addMasterCollectionBlockID($b, $id)
    {
        self::$mcBlockIDs[$b->getBlockID()] = $id;
    }

    public static function getMasterCollectionTemporaryBlockID($b)
    {
        if (isset(self::$mcBlockIDs[$b->getBlockID()])) {
            return self::$mcBlockIDs[$b->getBlockID()];
        }
    }

    public static function addPageTypeComposerOutputControlID(FormLayoutSetControl $control, $id)
    {
        self::$ptComposerOutputControlIDs[$id] = $control->getPageTypeComposerFormLayoutSetControlID();
    }

    public static function getPageTypeComposerFormLayoutSetControlFromTemporaryID($id)
    {
        if (isset(self::$ptComposerOutputControlIDs[$id])) {
            return self::$ptComposerOutputControlIDs[$id];
        }
    }

    public function importFiles($fromPath, $computeThumbnails = true)
    {
        $fh = new Importer();

        if (!$computeThumbnails) {
            $fh->setRescanThumbnailsOnImport(false);
            $helper = Core::make('helper/file');
        }
        $contents = Core::make('helper/file')->getDirectoryContents($fromPath);
        foreach ($contents as $filename) {
            if (!is_dir($filename)) {
                $fv = $fh->import($fromPath . '/' . $filename, $filename);
                if (!$computeThumbnails) {
                    $types = \Concrete\Core\File\Image\Thumbnail\Type\Type::getVersionList();
                    foreach ($types as $type) {
                        // since we provide the thumbnails, we're going to get a list of thumbnail types
                        // and loop through them, assigning them to all the files.
                        $thumbnailPath = $fromPath . '/' . $type->getHandle() . '/' . $filename;
                        if (file_exists($thumbnailPath)) {
                            $fv->importThumbnail($type, $thumbnailPath);
                        }
                    }
                }
            }
        }
    }
}
