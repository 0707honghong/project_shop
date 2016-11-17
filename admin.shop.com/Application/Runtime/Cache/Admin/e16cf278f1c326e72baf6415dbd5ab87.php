<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 添加新文章 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/css/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('index');?>">文章列表</a>
    </span>
    <span class="action-span1"><a href="__GROUP__">ECSHOP 文章中心</a></span>
    <span id="search_id" class="action-span1"> - 添加新文章</span>
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
                    <td class="label">文章名称：</td>
                    <td><input type="text" name="name" value="<?php echo ($row["name"]); ?>" size="30" />
                    <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">文章分类：</td>
                    <td>
                        <select name="article_category_id" id="option">
                            <option value="0" >请选择...</option>
                            <?php if(is_array($rows)): foreach($rows as $key=>$val): ?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">文章简介：</td>
                    <td>
                        <textarea rows="4" cols="30" name="intro"><?php echo ($row["intro"]); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否显示：</td>
                    <td>
                        <input type="radio" name="status" value="1" class="status"/> 是
                        <input type="radio" name="status" value="0" class="status"/> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">排序：</td>
                    <td>
                        <input type="text" name="sort" value="<?php echo ($row["sort"]); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">文章内容：</td>
                    <td>
                        <textarea rows="4" cols="30" name="content"><?php echo ($row["content"]); ?></textarea>
                    </td>
                </tr>
            </table>
            <div class="button-div">
                <input type="hidden" name="id" value="<?php echo ($row["id"]); ?>"/>
                <input type="hidden" name="article_id" value="<?php echo ($row["id"]); ?>"/>
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
    </div>
</div>

<div id="footer">
共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
<script type="text/javascript" src="/Public/js/jquery.min.js"></script>
<script type="text/javascript">
    $(function(){
        //分类回显
        $('#option').val([<?php echo ((isset($row["article_category_id"]) && ($row["article_category_id"] !== ""))?($row["article_category_id"]):1); ?>]);
        //回显状态
        $('.status').val([<?php echo ((isset($row["status"]) && ($row["status"] !== ""))?($row["status"]):1); ?>]);
    });
</script>
</body>
</html>