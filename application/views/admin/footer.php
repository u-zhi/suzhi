	<!--  <div class="ace-settings-container" id="ace-settings-container">
				<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
					<i class="icon-cog bigger-150"></i>
				</div>
				<div class="ace-settings-box" id="ace-settings-box">
					<div>
						<div class="pull-left">
							<select id="skin-colorpicker" class="hide">
								<option data-skin="default" value="#438EB9">#438EB9</option>
								<option data-skin="skin-1" value="#222A2D">#222A2D</option>
								<option data-skin="skin-2" value="#C6487E">#C6487E</option>
								<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
							</select>
						</div>
						<span>&nbsp; 选择皮肤</span>
					</div>
					<div>
						<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
						<label class="lbl" for="ace-settings-navbar"> 固定导航条</label>
					</div>
					<div>
						<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
						<label class="lbl" for="ace-settings-sidebar"> 固定滑动条</label>
					</div>
					<div>
						<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
						<label class="lbl" for="ace-settings-breadcrumbs">固定面包屑</label>
					</div>
					<div>
						<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
						<label class="lbl" for="ace-settings-rtl">切换到左边</label>
					</div>
				</div>
			</div>-->
		</div>
		<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
			<i class="icon-double-angle-up icon-only bigger-110"></i>
		</a>
	</div>
	<!-- ace scripts -->
	<script src="/public_source/www/assets/js/ace-elements.min.js"></script>
	<!--
	<script type="text/javascript">
	skin();//初始化skin
	function skin(){
		var d=$("#skin-colorpicker").find("option:selected").data("skin");
		var c=$(document.body);
		c.removeClass("skin-1 skin-2 skin-3");
		if(d!="default"){c.addClass(d)}
		if(d=="skin-1"){$(".ace-nav > li.grey").addClass("dark")}
		else{$(".ace-nav > li.grey").removeClass("dark")}
		if(d=="skin-2"){
			$(".ace-nav > li").addClass("no-border margin-1");
			$(".ace-nav > li:not(:last-child)").addClass("light-pink").find('> a > [class*="icon-"]').addClass("pink").end().eq(0).find(".badge").addClass("badge-warning")}
		else{
			$(".ace-nav > li").removeClass("no-border margin-1");
			$(".ace-nav > li:not(:last-child)").removeClass("light-pink").find('> a > [class*="icon-"]').removeClass("pink").end().eq(0).find(".badge").removeClass("badge-warning")
		}
		if(d=="skin-3"){
			$(".ace-nav > li.grey").addClass("red").find(".badge").addClass("badge-yellow")
		}else{
			$(".ace-nav > li.grey").removeClass("red").find(".badge").removeClass("badge-yellow")}
		}
	</script>  -->
	<script src="/public_source/www/assets/js/ace.min.js"></script>	
</body>
</html>