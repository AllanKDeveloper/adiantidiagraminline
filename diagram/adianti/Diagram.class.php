<?php
/**
 * Diagram Editor by Allan Kehl
 *
 * @version    1.0.0
 * @package    diagram
 * @author     Allan Kehl (https://github.com/AllanKDeveloper/)
 *
 * Site: https://www.draw.io/
 * Doc: https://github.com/jgraph/drawio
 * Help: https://support.draw.io/
 * 
 * Class makes use of the open-source draw.io project (contains the editor, css, img, etc.), it is in the diagram folder (diagram/).
 * diagram_script.php (diagram/) - used to save and delete diagrams via AJAX.
 * diagram.js (diagram / diagram.js) - as draw.io works only via JavaScript, this file contains the editor call to basic functions such as save and exit.
 */
class Diagram extends TField implements AdiantiWidgetInterface
{
    private static  $counter;
    
    /**
     * Class Constructor
     * @param $name Widet's name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        self::$counter ++;
        $this->widgetId = 'Diagram_'.self::$counter;
    }
    
    /**
     * Show the widget
     */
    public function show()
    {
        // Load the javascript
        $script = new TElement('script');
        $script->{'src'} = '../js/diagram.js';
        $script->{'type'} = 'text/javascript';
        $script->add(' '); // need to close html tag  
        $script->show();
      
        // Create image tag
        $diagram = new TElement('img');
        $diagram->id = 'image';
        $diagram->src = $this->value;

        // show the tag
        $diagram->show();
    }
}
