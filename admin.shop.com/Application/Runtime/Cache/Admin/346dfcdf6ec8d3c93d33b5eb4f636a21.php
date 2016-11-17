<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 添加新商品 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/css/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/main.css" rel="stylesheet" type="text/css" />
<link href="/Public/ext/uploadify/common.css" rel="stylesheet" type="text/css" />
<link href="/Public/ext/ztree/zTreeStyle.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('index');?>">商品列表</a>
    </span>
    <span class="action-span1"><a href="__GROUP__">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加新商品 </span>
    <div style="clear:both"></div>
</h1>

<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front" id="general-tab">通用信息</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="<?php echo U();?>" method="post">
            <table width="90%" id="general-table" align="center">
                <tr>
                    <td class="label">商品名称：</td>
                    <td><input type="text" name="name" value="<?php echo ($info["name"]); ?>"size="30" />
                    <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">商品LOGO：</td>
                    <td>
                        <input type="file" id="logo" size="50"/>
                        <input type="hidden" name="logo" value="<?php echo ($info["logo"]); ?>" id="logo_url"/>
                        <img src="<?php echo ($info["logo"]); ?>" id="logo_preview" style="max-width: 80px;max-height: 60px;margin-top: 10px;"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品相册：</td>
                    <td>
                        <div class="upload_gallery_box">
                            <?php if(is_array($gallery)): foreach($gallery as $key=>$gallery_val): ?><span class="gallery_box">
                                    <input type="hidden" name="path[]" value="<?php echo ($gallery_val["path"]); ?>"/>
                                    <img src="<?php echo ($gallery_val["path"]); ?>" style="max-width:150px; max-height: 150px;" />
                                </span><?php endforeach; endif; ?>
                        </div>
                        <input type="file" id="gallery" size="50"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品分类：</td>
                    <td>
                        <input type="hidden" name="goods_category_id" value="<?php echo ($info["goods_category_id"]); ?>" id="goods_category_id"/>
                        <ul id="goods_category_nodes" class="ztree"></ul>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品品牌：</td>
                    <td>
                        <?php echo arr2select($brand,'brand_id','id','name','brand');?>
                    </td>
                </tr>
                <tr>
                    <td class="label">供货商：</td>
                    <td>
                        <?php echo arr2select($supplier,'supplier_id','id','name','supplier');?>
                    </td>
                </tr>
                <tr>
                    <td class="label">市场价格：</td>
                    <td>
                        <input type="text" name="market_price" value="<?php echo ($info["market_price"]); ?>" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">本店价格：</td>
                    <td>
                        <input type="text" name="shop_price" value="<?php echo ($info["shop_price"]); ?>" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">库存：</td>
                    <td>
                        <input type="text" name="stock" size="8" value="<?php echo ($info["stock"]); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品状态：</td>
                    <td>
                        <input type="checkbox" class="goods_status" name="goods_status[]" value="1" /> 精品
                        <input type="checkbox" class="goods_status" name="goods_status[]" value="2" /> 新品
                        <input type="checkbox" class="goods_status" name="goods_status[]" value="4" /> 热销
                    </td>
                </tr>
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <input type="radio" name="is_on_sale" value="1" class="is_on_sale"/> 是
                        <input type="radio" name="is_on_sale" value="0" class="is_on_sale"/> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">状态：</td>
                    <td>
                        <input type="radio" name="status" value="1" class="status"/> 是
                        <input type="radio" name="status" value="0" class="status"/> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">排序：</td>
                    <td>
                        <input type="text" name="sort" size="5" value="<?php echo ($info["sort"]); ?>"/>
                    </td>
                </tr>
            </table>
            <div style="width: 900px;margin-left: 200px;">
                <p style="font-weight: bold;">商品描述：</p>
                <!-- 加载编辑器的容器 -->
                <script id="container" name="content" type="text/plain"><?php echo ($intro["content"]); ?></script>
            </div>
            <div class="button-div">
                <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>"/>
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
    </div>
</div>

<div id="footer">
共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
<script type="text/javascript" src="/Public/js/jquery.min.js"></script>
<script type="text/javascript" src="/Public/ext/uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript" src="/Public/ext/ztree/jquery.ztree.core.min.js"></script>
<script type="text/javascript" src="/Public/ext/ueditor/myueditor.config.js"></script>
<script type="text/javascript" src="/Public/ext/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="/Public/ext/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" src="/Public/ext/layer/layer.js"></script>
<!-- 配置文件 -->
<script type="text/javascript" src="/Public/ext/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/Public/ext/ueditor/ueditor.all.js"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    $(function(){
        //回显商品状态
        $('.goods_status').val(<?php echo ($info["goods_status"]); ?>);
        //回显上架状态
        $('.is_on_sale').val([<?php echo ($info["is_on_sale"]); ?>]);
        //回显状态
        $('.status').val([<?php echo ($info["status"]); ?>]);
        //回显品牌
        $('#brand').val(<?php echo ($info["brand_id"]); ?>);
        //回显供应商
        $('#supplier').val(<?php echo ($info["supplier_id"]); ?>);
        /**
         *uplodify上传文件
         **/
        //logo的uploadify
        $('#logo').uploadify({
            'swf'      : '/Public/ext/uploadify/uploadify.swf',
            'uploader' : "<?php echo U('Upload/upload');?>",
            buttonText : '选择文件',
//            fileTypeExts : '*.jpg',
            onUploadSuccess:function(file,data){
                //将响应数据转换为json对象
                data = $.parseJSON(data);
                console.log(data);
                if (data.status == 0) {
                    layer.msg(data.msg,{icon:5});
                }else{
                    layer.msg(data.msg,{icon:6});
                    $('#logo_url').val(data.url);
                    $('#logo_preview').attr('src',data.url);
                }
            },
        });
        //gallery的uplodify
        $('#gallery').uploadify({
            'swf'      : '/Public/ext/uploadify/uploadify.swf',
            'uploader' : "<?php echo U('Upload/upload');?>",
            buttonText : '选择文件',
            'multi'     :true,      //支持多文件上传
            onUploadSuccess:function(file,data){
                //将响应数据转换为json对象
                data = $.parseJSON(data);
                console.log(data);
                if (data.status) {
                    console.log(data.url);
                    var upload_gallery_box = $('.upload_gallery_box');
                    var html = '';
                    html += '<span class="gallery_box">';
                    html += '<input type="hidden" name="path[]" value="'+data.url+'">';
                    html += '<img src="'+data.url+'" style="max-width:150px;">';
                    html += '</span>';
                    $(html).appendTo(upload_gallery_box);
                    layer.msg('上传成功',{icon:6});
                }else{
                    layer.msg(data.msg,{icon:5});
                }
            },
        });
        //事件绑定
        $('.upload_gallery_box').on('dblclick','img',function(){
            var node = $(this);
            node.parent().remove();
        })
    });
    //editor编辑器
    var ue = UE.getEditor('container', {
        autoHeight: false
    });
    //对编辑器的操作最好在编辑器ready之后再做
    ue.ready(function() {
        //获取纯文本内容，
        var content = ue.getContent();
    });
    /**
     * ztree 无限极分层展示商品分类列表
     * @type {{data: {simpleData: {enable: boolean, pIdKey: string}}, callback: {onClick: Function}}}
     */
    var setting = {
        data: {
            simpleData: {
                enable: true,
                pIdKey: 'parent_id'
            }
        },
        callback:{
            //获取点击节点的名字id，将名字放在input框框中，然后隐藏
            onClick:function(event,tree_id,tree_node){
                $('#goods_category_name').val(tree_node.name);
                $('#goods_category_id').val(tree_node.id);
            },
            //当初发点击回调之前，先判断是否是叶子节点
            beforeClick:function(tree_id,tree_node){
                if (tree_node.isParent) {
                    layer.msg('请选择叶子节点',{icon:5,time:2000});
                    return false;
                }
            },
        },
    };
    var zNodes =<?php echo ($goodsCategory); ?>;

    $(document).ready(function(){
        var ztree_obj = $.fn.zTree.init($("#goods_category_nodes"), setting, zNodes);
//        ztree_obj.expandAll(true);  意思是展开层级
        ztree_obj.expandAll(true);
        <?php if(isset($info)): ?>//回显数据，选中父级节点
        //找到节点
        var parent_node = ztree_obj.getNodeByParam('id',<?php echo ($info["goods_category_id"]); ?>);
        ztree_obj.selectNode(parent_node);<?php endif; ?>
    });
</script>
</body>
</html>