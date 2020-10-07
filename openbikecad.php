<?php
class openbikecad{
    private $screen;
    public function __construct($w = 800, $h=400, $title='openbikecad'){
        $this->screen['width'] = $w;
        $this->screen['height'] = $h;
        $this->screen['title'] = $title;
    }
    public function __toString(){
        return "<embed src=\"bike.svg\" width=\"".($this->screen['width'])."\" height=\"".($this->screen['height'])."\"
type=\"image/svg+xml\" id=\"".$this->screen['title']."\"
pluginspage=\"http://www.adobe.com/svg/viewer/install/\" />";
    }

}


?>
