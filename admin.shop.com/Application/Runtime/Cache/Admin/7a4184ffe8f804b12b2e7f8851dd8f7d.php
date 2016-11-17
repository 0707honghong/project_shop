<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 文章列表 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/css/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/main.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/page.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('add');?>">添加新文章</a></span>
    <span class="action-span1"><a href="__GROUP__">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 文章列表 </span>
    <div style="clear:both"></div>
</h1>
<div class="form-div">
    <form action="<?php echo U();?>" name="searchForm">
        <img src="/Public/images/icon_search.gif" width="26" height="22" border="0" alt="search" />
        <input type="text" name="name" size="15" value="<?php echo I('get.name');?>" />
        <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>

<!-- 商品列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>编号</th>
                <th>文章名称</th>
                <th>文章分类</th>
                <th>文章简介</th>
                <th>是否显示</th>
                <th>排序</th>
                <th>插入时间</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($rows)): foreach($rows as $key=>$row): ?><tr>
                <td align="center"><?php echo ($row["id"]); ?></td>
                <td align="center" class="first-cell"><?php echo ($row["name"]); ?></td>
                <td align="center"><?php echo ($row["cname"]); ?></td>
                <td align="center"><?php echo ($row["intro"]); ?></td>
                <td align="center"><img src="/Public/images/<?php echo ($row["status"]); ?>.gif"/></td>
                <td align="center"><?php echo ($row["sort"]); ?></td>
                <td align="center"><?php echo ($row["inputtime"]); ?></td>
                <td align="center">
                    <a href="<?php echo U('view',['id'=>$row['id']]);?>" target="_blank" title="查看">查看</a>
                    <a href="<?php echo U('edit',['id'=>$row['id']]);?>" title="编辑">编辑</a>
                    <a href="<?php echo U('remove',['id'=>$row['id']]);?>" title="删除">删除</a>
                </td>
            </tr><?php endforeach; endif; ?>
        </table>

    <!-- 分页 -->
        <div class="page">
            <?php echo ($page_html); ?>
        </div>
    </div>
</form>

<div id="footer">
共执行 7 个查询，用时 0.028849 秒，Gzip 已禁用，内存占用 3.219 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>