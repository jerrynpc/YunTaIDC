<?php
include("../includes/common.php");
$admin = daddslashes($_SESSION['admin']);
$admin = $DB->query("SELECT * FROM `ytidc_admin` WHERE `username`='{$admin}'")->fetch_assoc();
if($admin['lastip'] != getRealIp() || $_SESSION['adminip'] != getRealIp()){
	@header("Location: ./login.php");
	exit;
}else{
	$permission = json_decode($admin['permission'], true);
	if(!in_array('*', $permission) && !in_array('main_order', $permission)){
		@header("Location: ./msg.php?msg=你无权限进行此操作！");
	}
}
if(isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] >= 1){
	$page = daddslashes($_GET['page']) - 1;
}else{
	$page = 0;
}
$start = $page * 10;
$status = daddslashes($_GET['status']);
if($status == "finish"){
	$result = $DB->query("SELECT * FROM `ytidc_order` WHERE `status`='已完成' ORDER BY `orderid` DESC LIMIT {$start}, 10");
}else{
	$result = $DB->query("SELECT * FROM `ytidc_order` ORDER BY `orderid` DESC LIMIT {$start}, 10");
}
include("./head.php");
?>
        <div class="bg-light lter b-b wrapper-md">
          <h1 class="m-n font-thin h3">交易记录</h1>
        </div>
        <div class="wrapper-md">
          <div class="panel panel-default">
            <div class="panel-heading">
              记录列表  <a href="?status=finish" class="btn btn-primary btn-xs btn-small">只显示已完成</a>
            </div>
            <div class="table-responsive">
              <table class="table table-striped b-t b-light">
                <thead>
                  <tr>
                    <th>订单编号</th>
                    <th>用户UID</th>
                    <th>内容</th>
                    <th>金额</th>
                    <th>操作</th>
                    <th>状态</th>
                  </tr>
                </thead>
                <tbody>
                	<?php
                  	 while($row = $result->fetch_assoc()){
                  	 	echo '<tr>
                    <td>'.$row['orderid'].'</td>
                    <td>'.$row['user'].'</td>
                    <td>'.$row['description'].'</td>
                    <td>'.$row['money'].'</td>
                    <td>'.$row['action'].'</td>
                    <td>'.$row['status'].'</td>
                  </tr>';
                  	 }
                  	?>
                </tbody>
              </table>
            </div>
		    <footer class="panel-footer">
		      <div class="row">
		        <div class="col-sm-12 text-right text-center-xs">                
		          <ul class="pagination pagination-sm m-t-none m-b-none">
		          	<?php
		          		if($page != 0){
		          			echo '<li><a href="./order.php?page='.$page.'"><i class="fa fa-chevron-left"></i></a></li>';
		          		}
		          		if($status == "finish"){
							$total = $DB->query("SELECT * FROM `ytidc_order` WHERE `status`='已完成'");
			          		$records = $total->num_rows;
			          		$total_pages = ceil($records / 10);
			            	for($i = 1;$i <= $total_pages; $i++){
			            		echo '<li><a href="./order.php?status=finish&page='.$i.'">'.$i.'</a></li>';
			            	}
			            	if($page+2 <= $total_pages){
			            		$next_page = $page + 2;
			            		echo '<li><a href="./order.php?status=finish&page='.$next_page.'"><i class="fa fa-chevron-right"></i></a></li>';
			            	}
						}else{
							$total = $DB->query("SELECT * FROM `ytidc_order`");
			          		$records = $total->num_rows;
			          		$total_pages = ceil($records / 10);
			            	for($i = 1;$i <= $total_pages; $i++){
			            		echo '<li><a href="./order.php?&page='.$i.'">'.$i.'</a></li>';
			            	}
			            	if($page+2 <= $total_pages){
			            		$next_page = $page + 2;
			            		echo '<li><a href="./order.php?&page='.$next_page.'"><i class="fa fa-chevron-right"></i></a></li>';
			            	}
						}
		            ?>
		            
		          </ul>
		        </div>
		      </div>
		    </footer>
          </div>
        </div>
<?php

include("./foot.php");
?>