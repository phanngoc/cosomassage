<?php
define('URL',  site_url('/viewcart'));
define('KEY_CUSTOM_POST_PRICE','wpcf-gia');
function bombay_cart_display_button_buy()
{
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[name="count_item"]').change(function(){
                console.log($(this).val());
            });
        });
    </script>
    <form action="<?php echo URL;?>" method="post" role="form">
        <input name="id_post" type="hidden" value="<?php echo get_the_ID();?>"/>
        <div class="form-group">
            <label for="count_item" style="display: block;">Số lượng</label>
            <input name="count_item" class="form-control" type="number" style="width: 100px;"/>
        </div>
        <button type="submit" class="btn btn-success btn-sm btn-block order-buy" ><h3>Đưa vào giỏ hàng</h3></button>    
    </form>
    <?php
}
add_action('init', 'bombay_myStartSession', 1);
add_action('wp_logout', 'bombay_myEndSession');
add_action('wp_login', 'bombay_myEndSession');

function bombay_myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function bombay_myEndSession() {
    session_destroy ();
}

function bombay_get_POST($name)
{
    if(isset($_POST[$name]))
    {
        return $_POST[$name];
    }
    else return '';
}

function bombay_cart_display_shopping_cart()
{
    $cart = array();
    if(isset($_SESSION['cart']))
    {
        $cart = (array)$_SESSION['cart'];
    }
    
    if(isset($_POST['count_item_in_cart'])) { 
        $index = 0;
        $cart = array() ;
        while($index < $_POST['count_item_in_cart'])
        {
            if(isset($_POST['ID'.$index]))
            {
                $item = array('id'=>$_POST['ID'.$index],'count'=>$_POST['COUNT'.$index]);
                array_push($cart, $item);
            }        
            $index++;
        }
    }
    
   
    $id_post = bombay_get_POST('id_post');
    $count_sp = bombay_get_POST('count_item');
    
    $nn = array('id'=>$id_post,'count'=>$count_sp);
   
    if($id_post!=''&&$count_sp!='')
    {
        $flag = false;
        foreach ($cart as $key_cart => $value_cart) {
            if($value_cart['id']==$id_post)
            {
                $flag = true;
                $cart[$key_cart]['count'] += intval($count_sp);
            }
        }
        if(!$flag)
        array_push($cart, $nn);
    }
    $_SESSION['cart'] = $cart;
    
    ?>
    <script type="text/javascript">
      $(document).ready(function(){
          $("#cart").find('.delete').on('click',function(){
              $(this).parent().parent().remove();
              count_item = $("#cart").find('tbody').find('tr').length;
              $("#cart").find('[name="count_item_in_cart"]').val(count_item);
          });
      });
    </script>
    <div class="table-responsive" id="cart">
        <form action="" method="POST">
        <table class="table cart">
            <input name="count_item_in_cart" type="hidden" value="<?php echo count($cart); ?>"/>
                <thead>
                    <tr>
                        <th data-field="id">ID</th>
                        <th data-field="name">Tên sản phẩm</th>
                        <th data-field="count">Số lượng</th>
                        <th data-field="delete">Xóa bỏ</th>
                        <th data-field="price">Đơn giá</th>
                    </tr>
                </thead>  
                <tbody>
                    <?php 
                        $total = 0;
                        foreach ($cart as $key => $value) {
                           echo "<tr>"; 
                           $id = $value['id'];
                           $count = $value['count'];
                           $price = get_post_meta($id, KEY_CUSTOM_POST_PRICE, true) ;
                           $info_post = get_post($id,ARRAY_A);
                           //var_dump($info_post);
                           echo "<input type='hidden' name='ID$key' value='$id'/>";
                           echo "<td>$id</td>";
                           echo "<td><a href='".  get_permalink($id)."'>".$info_post['post_title']."</a></td>";
                           echo "<td><input name='COUNT$key' class='form-control' value='$count' type='number' style='width:160px;'/></td>";
                           echo "<td><button class='btn btn-danger delete'>Delete</button></td>";
                           echo "<td><p>$price</p></td>";
                           $total += $count * intval($price);
                        }
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><span class="text-info">Tổng cộng :</span><span class="text-info"><?php echo $total ; ?></span></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th><button class="btn btn-primary" type="submit">Cập nhật giỏ hàng</button></th>
                        <?php 
                          $url_from = $_SERVER['HTTP_REFERER'];
                          $url_current = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
                          if(!isset($_SESSION['PAGE_TO']))
                          {
                              $_SESSION['PAGE_TO'] = $url_from;
                          }
                          else
                          {
                              if($url_from!=$url_current)
                              {
                                  $_SESSION['PAGE_TO'] = $url_from;
                              }
                              else
                              {
                                  $url_from = $_SESSION['PAGE_TO'];
                              }
                              
                          }
                        ?>
                        <th><a class="btn btn-primary" href="<?php echo $url_from;?>">Tiếp tục mua hàng</a></th>
                    </tr>
                </tfoot>
        </table>
        </form>    
        <div class="order">
            <form action="" method="POST" role="form" id="form-order">
                <div class="wrap-title-page">
                    <h4 class="header">Vui lòng điền đầy đủ thông tin</h4>
                </div>
            <div class="form-group">
                <label for="fullname">Họ và tên</label>
                <input type="text" class="form-control" name="fullname"/>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" class="form-control" name="address" />
            </div>
            <div class="form-group">
                <label for="phone">Điên thoại</label>
                <input type="text" class="form-control" name="phone" />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" type="email"/>
            </div>
                <input type="submit" class="btn btn-success submit" value="Thanh toán" />
            </form>
            <style type="text/css">
                .error{
                    color : red;
                }
            </style>
            <script src="<?php echo plugins_url("/js/jquery.validate.js",__FILE__);?>"></script>
            <script src="<?php echo plugins_url("/js/additional-methods.js",__FILE__);?>"></script>
            <script type="text/javascript">
                $().ready(function() {
		
                    // validate signup form on keyup and submit
                    $("#form-order").validate({
                            rules: {
                                    fullname: {
                                            required: true,
                                            minlength: 5,
                                    },
                                    address: {
                                            required: true,
                                            minlength: 5,
                                    },
                                    phone: {
                                            required: true,
                                            minlength: 6,
                                            digits : true,
                                    },
                                    email: {
                                            required: true,
                                            email: true,
                                    },
                            },
                            messages: {
                                    fullname : {
                                            required: "Vui lòng điền họ tên đầy đủ",
                                            minlength: "Họ và tên phải ít nhất 5 kí tự",
                                    },
                                    address: {
                                            required: "Vui lòng điền địa chỉ",
                                            minlength: "Địa chỉ ít nhất 5 kí tự",
                                    },
                                    phone: {
                                            required: "Vui lòng điền số điện thoại",
                                            minlength: "Số điện thoại phải tối thiểu 6 kí tự",
                                            digits : "Chỉ được điền số",
                                    },
                                    email: {
                                        required: "Vui lòng điền địa chỉ email",
                                        email : "Địa chỉ email không hợp lệ", 
                                    }
                            }
                    });
                });
            </script>
        </div>
    </div>
    <?php
}

