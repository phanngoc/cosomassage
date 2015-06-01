<?php
/*
Template Name: Deposit
*/
?>
<?php $mts_options = get_option('point'); ?>
<?php get_header(); ?>
<div id="page" class="home-page">
	<div class="content">
		<div class="article">
                    <div class="wrap">
                        <?php 
                        if (!is_user_logged_in()){
                            if(isset($_GET['login']) && $_GET['login']=='failed')
                            {
                                ?>
                                <div id="myAlert" class="alert alert-warning">
                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                    <strong>Warning!</strong> Tên đăng nhập hoặc mật khẩu bi sai
                                </div>
                                <?php
                            }
                            thememe_wp_login_form();
                        }
                        else
                        {
                        ?>
                        <h2 class="title">Thông tin đặt hẹn</h2>
                        <form role="form" method="POST" action="">
                            <div class="form-group">
                            <label for="fullname">Họ và tên</label>
                            <input type="text" name="fullname" class="form-control">
                            </div>
                            <br>
                            <div class="form-group">
                            <label for="gender">Giới tính</label>
                            <select name="gender" class="form-control">
                                <option value="male">Nam</option>
                                <option value="female">Nữ</option>
                            </select>
                            </div>
                            
                            <br>
                            <div class="form-group">
                            <label for="quantity">Số lượng</label>
                            <input type="number" name="quantity" min="1" max="5"  class="form-control">
                            </div>
                            <br>
                            <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <input type="text" name="address"  class="form-control">
                            </div>
                            <br>
                            <div class="form-group">
                            <label for="typeroom">Loại phòng</label>
                             <select name="typeroom" class="form-control">
                                <option value="normal">Thường</option>
                                <option value="vip">Vip</option>
                            </select>
                            </div>
                            <?php display_select_choose_type_massage();?>
                            <div class="form-group">
                                <label for="date">Ngày</label>
                                <input type="text" name="date" id="date">
                                <script type="text/javascript">
                                    $( "#date" ).datepicker(); 
                                </script>
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                         </form> 
                        <?php } ?>
                    </div>
		</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>