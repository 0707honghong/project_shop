<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 商品列表 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/css/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/main.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/page.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('add');?>">添加新商品</a></span>
    <span class="action-span1"><a href="__GROUP__">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 商品列表 </span>
    <div style="clear:both"></div>
</h1>
<div class="form-div">
    <form action="" name="searchForm" method="get">
        <img src="/Public/images/icon_search.gif" width="26" height="22" border="0" alt="search" />
        <!-- 分类 -->
        <select name="goods_category_id" id="select_goods_category">
            <option value="0">所有分类</option>
            <?php if(is_array($goodsCategory)): foreach($goodsCategory as $key=>$val): ?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; ?>
        </select>
        <!-- 品牌 -->
        <select name="brand_id" id="select_brand">
            <option value="0">所有品牌</option>
            <?php if(is_array($brand)): foreach($brand as $key=>$val): ?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; ?>
        </select>
        <!-- 推荐 -->
        <select name="goods_status" id="select_goods_status">
            <option value="0">全部</option>
            <option value="1">精品</option>
            <option value="2">新品</option>
            <option value="4">热销</option>
        </select>
        <!-- 上架 -->
        <select name="is_on_sale" id="select_is_on">
            <option value=''>全部</option>
            <option value="1">上架</option>
            <option value="0">下架</option>
        </select>
        <!-- 关键字 -->
        关键字 <input type="text" name="keyword" size="15" />
        <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>

<!-- 商品列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>编号</th>
                <th>商品名称</th>
                <th>商品LOGO</th>
                <th>货号</th>
                <th>市场价格</th>
                <th>本店价格</th>
                <th>上架</th>
                <th>精品</th>
                <th>新品</th>
                <th>热销</th>
                <th>排序</th>
                <th>库存</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($rows)): foreach($rows as $key=>$row): ?><tr>
                <td align="center"><?php echo ($row["id"]); ?></td>
                <td align="center" class="first-cell"><?php echo ($row["name"]); ?></td>
                <td align="center"><img src="<?php echo ($row["logo"]); ?>" style="max-width: 80px;height: 50px;"/></td>
                <td align="center"><?php echo ($row["sn"]); ?></td>
                <td align="center"><?php echo ($row["market_price"]); ?></td>
                <td align="center"><?php echo ($row["shop_price"]); ?></td>
                <td align="center"><img src="<?php if(($row["is_on_sale"] == 1)): ?>/Public/images/yes.gif <?php else: ?> /Public/images/no.gif<?php endif; ?>"/></td>
                <td align="center"><img src="<?php if(($row["is_best"] == 1)): ?>/Public/images/yes.gif <?php else: ?> /Public/images/no.gif<?php endif; ?>"/></td>
                <td align="center"><img src="<?php if(($row["is_new"] == 2)): ?>/Public/images/yes.gif <?php else: ?> /Public/images/no.gif<?php endif; ?>"/></td>
                <td align="center"><img src="<?php if(($row["is_hot"] == 4)): ?>/Public/images/yes.gif <?php else: ?> /Public/images/no.gif<?php endif; ?>"/></td>
                <td align="center"><?php echo ($row["sort"]); ?></td>
                <td align="center"><?php echo ($row["stock"]); ?></td>
                <td align="center">
                <a href="<?php echo U('view',['id'=>$row['id']]);?>" target="_blank" title="查看"><img src="/Public/images/icon_view.gif" width="16" height="16" border="0" /></a>
                <a href="<?php echo U('edit',['id'=>$row['id']]);?>" title="编辑"><img src="/Public/images/icon_edit.gif" width="16" height="16" border="0" /></a>
                <a href="<?php echo U('remove',['id'=>$row['id']]);?>" onclick="" title="回收站"><img src="/Public/images/icon_trash.gif" width="16" height="16" border="0" /></a></td>
            </tr><?php endforeach; endif; ?>
        </table>

    <!-- 分页开始 -->
        <div class="page">
            <?php echo ($page_html); ?>
        </div>
    <!-- 分页结束 -->
    </div>
</form>

<div id="footer">
共执行 7 个查询，用时 0.028849 秒，Gzip 已禁用，内存占用 3.219 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
</div>
</body>
</html>