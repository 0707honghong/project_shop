<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 添加角色 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/css/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/main.css" rel="stylesheet" type="text/css" />
<link href="/Public/ext/uploadify/common.css" rel="stylesheet" type="text/css" />
<link href="/Public/ext/ztree/zTreeStyle.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('index');?>">角色列表</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加角色 </span>
    <div style="clear:both"></div>
</h1>
<div class="main-div">
    <form method="post" action="<?php echo U();?>"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">角色名称：</td>
                <td>
                    <input type="text" name="name" size="50" value="<?php echo ($row["name"]); ?>"  />
                </td>
            </tr>
            <tr>
                <td class="label">描述：</td>
                <td>
                    <textarea name="intro" rows="5" cols="50" style="resize: none"><?php echo ($row["intro"]); ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="label">排序：</td>
                <td>
                    <input type="text" name="sort" size="50" value="<?php echo ($row["sort"]); ?>"  />
                </td>
            </tr>
            <tr>
                <td class="label">权限：</td>
                <td>
                    <div id="permission_ids">

                    </div>
                    <ul id="permissions" class="ztree"></ul>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><br />
                    <input type="hidden" name="id" value="<?php echo ($row["id"]); ?>"/>
                    <input type="submit" class="button" value=" 确定 " />
                    <input type="reset" class="button" value=" 重置 " />
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="footer">
共执行 1 个查询，用时 0.018952 秒，Gzip 已禁用，内存占用 2.197 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
<script type="text/javascript" src="/Public/js/jquery.min.js"></script>
<script type="text/javascript" src="/Public/ext/ztree/jquery.ztree.core.min.js"></script>
<script type="text/javascript" src="/Public/ext/ztree/jquery.ztree.excheck.min.js"></script>
<script type="text/javascript" src="/Public/ext/layer/layer.js"></script>
<script type="text/javascript">
    var setting = {
        check: {
            enable: true
        },
        data: {
            simpleData: {
                enable: true,
                pIdKey: 'parent_id'
            }
        },
        callback:{
            //获取点击节点的名字id，将名字放在input框框中，然后隐藏
            onCheck:function(event,tree_id,tree_node){
                var nodes = ztree_obj.getCheckedNodes(true);
                var div = $('#permission_ids');
                div.empty();
                $(nodes).each(function(i,v){
                    var html = '<input type="hidden" name="permission_id[]" value="'+ v.id+'"/>';
                    console.log(html);
                    $(html).appendTo(div);
                });
            },
        },
    };
    var zNodes =<?php echo ($permissions); ?>;
    var ztree_obj;
    $(document).ready(function(){
        ztree_obj = $.fn.zTree.init($("#permissions"), setting, zNodes);
//        ztree_obj.expandAll(true);  意思是展开层级
        ztree_obj.expandAll(true);

        //编辑回显数据
        <?php if(isset($row)): ?>var permission_ids = <?php echo ($row["permission_ids"]); ?>;
            $(permission_ids).each(function(i,v){
                var node = ztree_obj.getNodeByParam('id',v);
                ztree_obj.checkNode(node,true,false,true)
            });<?php endif; ?>
    });
</script>
</body>
</html>