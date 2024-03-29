<?php
/*
  
*/
function bombay_select_chosen($class,$placeholder="",$data=array(),$width="100%")
{
    ?>
    <select class="<?php echo $class;?>" data-placeholder="<?php echo $placeholder;?>">
        <?php 
            foreach ($data as $key => $value) {
                echo "<option>".$value."</option>";
            }
        ?>
    </select>
    <script type="text/javascript" src="<?php echo plugins_url('/js/chosen/chosen.jquery.js',__FILE__);?>"></script>
    <script type="text/javascript">
        $(".<?php echo $class;?>").chosen({width:"<?php echo $width;?>"});
    </script>
    <link rel="stylesheet" type=text/css href="<?php echo plugins_url('/js/chosen/chosen.css',__FILE__);?>" />
    <?php
} 


//http://fgnass.github.io/spin.js/#!
function bombay_spin($id)
{
?>
    <script src="<?php echo plugins_url('/js/spin.js',__FILE__); ?>"></script>
    <script type="text/javascript">
        var opts = {
            lines: 13, // The number of lines to draw
            length: 20, // The length of each line
            width: 10, // The line thickness
            radius: 30, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 0, // The rotation offset
            direction: 1, // 1: clockwise, -1: counterclockwise
            color: '#000', // #rgb or #rrggbb or array of colors
            speed: 1, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: '50%', // Top position relative to parent
            left: '50%' // Left position relative to parent
          };
          var target = document.getElementById('<?php echo $id;?>');
          var spinner = new Spinner(opts).spin(target);
          target.appendChild(spinner.el);
          //target.stop();
    </script>    
<?php    
}

function bombay_var_dump_js()
{
    ?>
    <script type="text/javascript">
        function var_dump() {
            //  discuss at: http://phpjs.org/functions/var_dump/
            // original by: Brett Zamir (http://brett-zamir.me)
            // improved by: Zahlii
            // improved by: Brett Zamir (http://brett-zamir.me)
            //  depends on: echo
            //        note: For returning a string, use var_export() with the second argument set to true
            //        test: skip
            //   example 1: var_dump(1);
            //   returns 1: 'int(1)'

            var output = '',
              pad_char = ' ',
              pad_val = 4,
              lgth = 0,
              i = 0;

            var _getFuncName = function(fn) {
              var name = (/\W*function\s+([\w\$]+)\s*\(/)
                .exec(fn);
              if (!name) {
                return '(Anonymous)';
              }
              return name[1];
            };

            var _repeat_char = function(len, pad_char) {
              var str = '';
              for (var i = 0; i < len; i++) {
                str += pad_char;
              }
              return str;
            };
            var _getInnerVal = function(val, thick_pad) {
              var ret = '';
              if (val === null) {
                ret = 'NULL';
              } else if (typeof val === 'boolean') {
                ret = 'bool(' + val + ')';
              } else if (typeof val === 'string') {
                ret = 'string(' + val.length + ') "' + val + '"';
              } else if (typeof val === 'number') {
                if (parseFloat(val) == parseInt(val, 10)) {
                  ret = 'int(' + val + ')';
                } else {
                  ret = 'float(' + val + ')';
                }
              }
              // The remaining are not PHP behavior because these values only exist in this exact form in JavaScript
              else if (typeof val === 'undefined') {
                ret = 'undefined';
              } else if (typeof val === 'function') {
                var funcLines = val.toString()
                  .split('\n');
                ret = '';
                for (var i = 0, fll = funcLines.length; i < fll; i++) {
                  ret += (i !== 0 ? '\n' + thick_pad : '') + funcLines[i];
                }
              } else if (val instanceof Date) {
                ret = 'Date(' + val + ')';
              } else if (val instanceof RegExp) {
                ret = 'RegExp(' + val + ')';
              } else if (val.nodeName) { // Different than PHP's DOMElement
                switch (val.nodeType) {
                  case 1:
                    if (typeof val.namespaceURI === 'undefined' || val.namespaceURI === 'http://www.w3.org/1999/xhtml') { // Undefined namespace could be plain XML, but namespaceURI not widely supported
                      ret = 'HTMLElement("' + val.nodeName + '")';
                    } else {
                      ret = 'XML Element("' + val.nodeName + '")';
                    }
                    break;
                  case 2:
                    ret = 'ATTRIBUTE_NODE(' + val.nodeName + ')';
                    break;
                  case 3:
                    ret = 'TEXT_NODE(' + val.nodeValue + ')';
                    break;
                  case 4:
                    ret = 'CDATA_SECTION_NODE(' + val.nodeValue + ')';
                    break;
                  case 5:
                    ret = 'ENTITY_REFERENCE_NODE';
                    break;
                  case 6:
                    ret = 'ENTITY_NODE';
                    break;
                  case 7:
                    ret = 'PROCESSING_INSTRUCTION_NODE(' + val.nodeName + ':' + val.nodeValue + ')';
                    break;
                  case 8:
                    ret = 'COMMENT_NODE(' + val.nodeValue + ')';
                    break;
                  case 9:
                    ret = 'DOCUMENT_NODE';
                    break;
                  case 10:
                    ret = 'DOCUMENT_TYPE_NODE';
                    break;
                  case 11:
                    ret = 'DOCUMENT_FRAGMENT_NODE';
                    break;
                  case 12:
                    ret = 'NOTATION_NODE';
                    break;
                }
              }
              return ret;
            };

            var _formatArray = function(obj, cur_depth, pad_val, pad_char) {
              var someProp = '';
              if (cur_depth > 0) {
                cur_depth++;
              }

              var base_pad = _repeat_char(pad_val * (cur_depth - 1), pad_char);
              var thick_pad = _repeat_char(pad_val * (cur_depth + 1), pad_char);
              var str = '';
              var val = '';

              if (typeof obj === 'object' && obj !== null) {
                if (obj.constructor && _getFuncName(obj.constructor) === 'PHPJS_Resource') {
                  return obj.var_dump();
                }
                lgth = 0;
                for (someProp in obj) {
                  lgth++;
                }
                str += 'array(' + lgth + ') {\n';
                for (var key in obj) {
                  var objVal = obj[key];
                  if (typeof objVal === 'object' && objVal !== null && !(objVal instanceof Date) && !(objVal instanceof RegExp) && !
                    objVal.nodeName) {
                    str += thick_pad + '[' + key + '] =>\n' + thick_pad + _formatArray(objVal, cur_depth + 1, pad_val,
                      pad_char);
                  } else {
                    val = _getInnerVal(objVal, thick_pad);
                    str += thick_pad + '[' + key + '] =>\n' + thick_pad + val + '\n';
                  }
                }
                str += base_pad + '}\n';
              } else {
                str = _getInnerVal(obj, thick_pad);
              }
              return str;
            };

            output = _formatArray(arguments[0], 0, pad_val, pad_char);
            for (i = 1; i < arguments.length; i++) {
              output += '\n' + _formatArray(arguments[i], 0, pad_val, pad_char);
            }

            var isNode = typeof module !== 'undefined' && module.exports;
            if (isNode) {
              return console.log(output);
            }

            var d = this.window.document;

            if (d.body) {
              this.echo(output);
            } else {
              try {
                d = XULDocument; // We're in XUL, so appending as plain text won't work
                this.echo('<pre xmlns="http://www.w3.org/1999/xhtml" style="white-space:pre;">' + output + '</pre>');
              } catch (e) {
                this.echo(output); // Outputting as plain text may work in some plain XML
              }
            }
          }
    </script>
    <?php
}