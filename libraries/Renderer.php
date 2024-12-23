<?php


class Renderer {
    public function __construct() {
        
    }

    // Render the template including the current page content.
  public function render(string $content = '') {       
    echo $content;
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