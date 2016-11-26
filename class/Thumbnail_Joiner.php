<?php
/**
 * This class uses Imagick to join some images into an animated gif
 *
 * @author    Lorenzo Alberton <lorenzo@ibuildings.com>
 * @copyright 2008-2009 Lorenzo Alberton
 * @license   http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 */
class Thumbnail_Joiner
{
    /**
     * @var integer delay between images (in milliseconds)
     */
    protected $delay = 50;

    /**
     * @var array
     */
    protected $images = array();

    /**
     * @param integer $delay between images
     */
    public function __construct($delay = 50) {
        $this->delay = $delay;
    }

    /**
     * Load an image from file
     *
     * @param string $filename
     *
     * @return void
     */
    public function addFile($image) {
        $this->images[] = file_get_contents($image);
    }

    /**
     * Load an image
     *
     * @param string $image binary image data
     *
     * @return void
     */
    public function add($image) {
        $this->images[] = $image;
    }

    /**
     * Generate the animated gif
     *
     * @return string binary image data
     */
    public function get() {
        $animation = new Imagick();
        $animation->setFormat('gif');
        foreach ($this->images as $image) {
            $frame = new Imagick();
            $frame->readImageBlob($image);
            $animation->addImage($frame);
            $animation->setImageDelay($this->delay);
        }
        return $animation->getImagesBlob();
    }

    /**
     * Save the animated gif to file
     *
     * @param string $outfile output file name
     *
     * @return void
     */
    public function save($outfile) {
        file_put_contents($outfile, $this->get());
    }
}
?>