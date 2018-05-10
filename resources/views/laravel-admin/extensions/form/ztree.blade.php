<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{ $label }}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')
		
		<!-- 树形 -->
		<div class="zTreeDemoBackground">
			<div>
				<input type="text" id="{{ $id }}-ztreeInput" class="form-control {{$class}}" readonly style="width: 100%;" />
				<!-- 文本框 -->
				<input type="hidden" id="{{ $id }}-hidden-ztreeInput" class="form-control {{$class}}" style="width: 100%;" name="{{$name}}" {!! $attributes !!} />
				<!-- 图标 -->
				<i id="{{ $id }}-ztreeInput-icon" class="fa fa-caret-down" aria-hidden="true" style="position:absolute; right: 25px; top: 10px;"></i>
			</div>

			<div id="{{ $id }}-menuContent" class="menuContent" style="width:100%; display:none; position:absolute; z-index: 900; color:#333; background-color:#fff;">
				<!-- 菜单 -->
				<ul id="{{ $id }}-ztreeMenu" class="ztree" style="margin-top:0; width:100%;border: 1px solid #617775;background: white;overflow-y:scroll;overflow-x:auto;height:200px;" ></ul>
			</div>
		</div>

		<script type="text/javascript">
			;$(function(){
				var onBodyDown = function (event) {
					if (!(event.target.id == "{{ $id }}-ztreeInput" || event.target.id == "{{ $id }}-menuContent" || $(event.target).parents("#{{ $id }}-menuContent").length > 0)) {
						hideMenu();
					}
				}
				var hideMenu = function () {
					// 隐藏菜单
					$("#{{ $id }}-menuContent").fadeOut("fast");
					// icon改变为向下
					$("#{{ $id }}-ztreeInput-icon").addClass("fa-caret-down");
					$("#{{ $id }}-ztreeInput-icon").removeClass("fa-caret-up");

					$("body").unbind("mousedown", onBodyDown);
				};

				// 绑定点击事件
				$("#{{ $id }}-ztreeInput").on('click', function () {
					// 如果隐藏则让它显示, 否则隐藏
					if ( $("#{{ $id }}-menuContent").is(":hidden") ) {
						// icon改变为向上
						$("#{{ $id }}-ztreeInput-icon").addClass("fa-caret-up");
						$("#{{ $id }}-ztreeInput-icon").removeClass("fa-caret-down");

						var cityObj = $("#{{ $id }}-ztreeInput");
						// 设置宽度
						// 获取宽度
						// $('div').width();     获取：区块的本身宽度
						// $('div').outerWidth();     获取：区块的宽度+padding宽度+border宽度
						// $('div').outerWidth(true);    获取：区块的宽度+padding宽度+border宽度+margin的宽度
						// 获取高度
						// $('div').height();     获取：区块的本身高度
						// $('div').outerHeight();     获取：区块的高度+padding高度+border高度
						// $('div').outerHeight(true);    获取：区块的高度+padding高度+border高度+margin的高度
						$("#{{ $id }}-menuContent").css({ width: cityObj.outerWidth(true) }).slideDown("fast");
					} else {
						// icon改变为向下
						$("#{{ $id }}-ztreeInput-icon").addClass("fa-caret-down");
						$("#{{ $id }}-ztreeInput-icon").removeClass("fa-caret-up");
						$("#{{ $id }}-menuContent").fadeOut("fast");
					}

					// 其他地方点击, 下拉框小时
					$("body").bind("mousedown", onBodyDown);
				});
			});


			// 回填数据			
			function backfillZtree{{ $id }}(){
				@if (old($column, $value))
					var val = "{{ is_array(old($column, $value)) ? implode(',', old($column, $value)) : old($column, $value) }}";
					if ($.isArray( val )) {
						// 判断是否为一个数组
						// alert("array");
						val = val;
					} else if ($.isPlainObject(val)) {
						// 判断指定参数是否是一个纯粹的对象
						// alert("object");
						val = [val];
					} else if (val.indexOf(',') != -1) {//joinsign 分割符
						alert("string array");
						val = val.split(',');
					} else {
						val = [val];
						// 回填单值
						$("#{{ $id }}-hidden-ztreeInput").val(val);
					}

					// ztree回填选中
					if(val.length > 0) {
						$.each(val,function(i,v){
							var node = $.isPlainObject(v) ? $.fn.zTree.getZTreeObj("{{ $id }}-ztreeMenu").getNodeByParam(v.key,v.value):
							$.fn.zTree.getZTreeObj("{{ $id }}-ztreeMenu").getNodeByParam("id", v);

							if(node) {
								$.fn.zTree.getZTreeObj("{{ $id }}-ztreeMenu").checkNode(node, true, false, false);
							}
						});
					}

					//如果是数组的时候

					//如果是单值的时候

					//input框中的数据
					var nodes = $.fn.zTree.getZTreeObj("{{ $id }}-ztreeMenu").getCheckedNodes(true);
					var v = "";
					var name = "";
					var joinsign = ",";
					if(nodes){
						for (var i=0, l = nodes.length; i<l; i++) {
							v += nodes[i].name + (joinsign ? joinsign : ";");
						}
						
						if (v.length > 0 ) {
							v = v.substring(0, v.length-1);
						}

						$("#{{ $id }}-ztreeInput").val(v);
					}

				@endif
			}
		</script>
        <!-- <textarea class="form-control {{ $class }}" name="{{$name}}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($column, $value) }}</textarea> -->

        @include('admin::form.help-block')

    </div>
</div>