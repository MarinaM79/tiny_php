<html lang="zh-CN">
<head>
	<meta charset="utf-8"/>
	<title>{{env('APPNAME')}}</title>
	<!-- tiny_auto -->
	<style>
	*{
		user-select:none;
		-moz-user-select:none;
		-ms-user-select:none;
		-webkit-user-select:none;
	}
	body{
		height:100%;
		overflow:hidden;
	}
	  .target_url{
		  flex-grow:1,
		  font-size:20px,
		  padding:25px;
		  height:75px;
		  line-height:75px;
		  width:33%;
		  cursor:pointer
	  }
	</style>
</head>
<body>
	<div class="main" style="width:95%;margin: 0 auto;text-align:center">
		<el-container style="margin:180px auto">
		  <el-header style="font-size:55px;text-align:center">{{env('APPNAME')}}</el-header>
		  <el-main style="margin:100px auto;display:flex;width:800px;text-align:center;">
			<div class="target_url" @click="go('Gitee')">Gitee</div>
			<div class="target_url" @click="go('Github')">Github</div>
			<div class="target_url" @click="go('Coding')">Coding</div>
		  </el-main>
		</el-container>
	</div>
	<script>
		let vue = new Vue({
			el:'.main',
			data:{
				target_url:
				{
					Gitee: 'https://gitee.com/zjz17683954109/tiny_php',
					Github: 'https://github.com/17683954109/tiny_php',
					Coding: 'https://dev.tencent.com/u/TheShy17683954109/p/tiny_php'
				}
			},
			methods:{
				go(url){
					window.open(this.target_url[url]);
				}
			}
		})
	</script>
</body>
</html>