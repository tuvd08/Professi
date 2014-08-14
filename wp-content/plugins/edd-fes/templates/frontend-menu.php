<?php
$task = ! empty( $_GET['task'] ) ? $_GET['task'] : '';
$icon_css = apply_filters( "edd_fes_vendor_dashboard_menu_icon_css", " icon-white" ); //else icon-black/dark
$menu_items = EDD_FES()->vendors->get_vendor_dashboard_menu();
?>
<nav class="fes-vendor-menu">
		<ul>
			<?php 
			foreach ($menu_items as $item => $values){
			?>
			<li class="<?php if( in_array($task, $values["task"])) echo "active"; ?>">
				<a href='<?php echo add_query_arg( 'task', $values["task"][0], get_permalink() ); ?>'>
					<i class="icon icon-<?php echo $values["icon"]; ?> <?php echo $icon_css; ?>"></i> <span class="hidden-phone hidden-tablet"><?php echo $values["name"]; ?></span>
				</a>
			</li>
			<?php
			}
			?>
		</ul>  
</nav>