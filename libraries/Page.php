<?php

namespace Libraries;

class Page {

  public function __construct(
    private string $template_dir,
    private string $header_file = 'header.php',
    private string $footer_file = 'footer.php',
    private string $layout_template = 'layout.php',
    private string $page_file = 'page.php',
    private array $data = []
  ) {
      
  }

  public function renderPartial() {
    
  }

  // Render the template including the current page content.
  public function render() {       
    ob_start();
    require $this->page_file; // PAGE MAIN CONTENT no need to checks, constructor takes care of that
    $content = ob_get_clean();    
    require $this->layout_template; // Render the Page Template
  }

  // For partial contents or page parts files like: header, sidebar or footer.
  // Can be used by /templates/layout and /templates/pages, check out [=> /templates/layout/page.php]
  public function renderPartial($partial_file, $partial_data = []) {
    $partial_data = array_merge($this->data, $partial_data);
    extract($partial_data);
    $template_file = $this->template_path . $partial_file;
    if(file_exists($template_file)){
        require $template_file;
    }
  }
}



class Renderer {


  public function render() { 

  }

  public function renderPartial($partial_file, $partial_data = []) {
    $partial_data = array_merge($this->data, $partial_data);
    extract($partial_data);
    $template_file = $this->template_path . $partial_file;
    if(file_exists($template_file)){
        require $template_file;
    }
  }

}

class PageView extends Renderer {
  
  

}
