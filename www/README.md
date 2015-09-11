### API Usage
---
**写在前面**


全部用户登入时进入桌面并看到所有文档，文档的操作不限制于具体用户，即增删改不以用户ID为`where`条件。

**约定** 

1. `code:0`即成功。

2. 返回 `json` 格式:

	```
		{
			"code": {int},
			"data": {array | null | ''},
			"msg": 'string'
		}
	```
3. ...
4. ...

---

* 显示所有文件（桌面）
	
	访问：`/directory/desktop`
	
	参数：无
	
	返回：
	
	```
	//success:
	{
		"code": 0,
		"data": {
			"docs": [
				{
					"id": "1",
					"uid": "2",
					"dirid": "0",
					"docname": "newo",
					"status": "0",
					"lock": "0",
					"create_time": "1441782695",
					"update_time": "1441783514"
				},
				{...},
				{...}		
			]
		},
		"msg": "success"
	}
	
	//or false
	{
		"code": 1, #或者其他大于0的
		"data": null,
		"msg": "fail"
	}
	```
	
* 修改文档名称

	访问：`/document/update`
	
	参数：`new_name`, `doc_id`
	
	返回：
	
	```
	{
		"code": 0,
		"data": null,
		"msg": 'success'
	}
	// false
	{
		"code": 1,
		"data": null,
		"msg": 'fail'
	}
	```
	
* 显示文档内容（文档下所有 section）

	访问：`/document/show`
	
	参数：`doc_id`
	
	返回：
	
	```
		{
			"code": 0,
			"data": [
				{
					"content": "something need to be keepd"
				},
				{
					"content": "Helo ladfadodfayh lla herei"
				}
			],
			"msg": "success"
		}
		// false
		{
			"code": {>0},
			"data": '',
			"msg": 'fail'
		}
	```
	
* 添加文档

	访问：`/document/add`
	
	参数：`dir_id`, `doc_name` *注：目前不用传 `dir_id`, 全局默认`0`*
	
	返回：
	
	```
		{
			"code": 0,
			"data": '',
			"msg": 'success'
		}
		
		// false
		{
			"code": 1,
			"data": '',
			"msg": 'fail'
		}

	```
	
* 删除文档 (软删/强制删)
	
	访问： `/document/soft_del`
	
	参数：`doc_id`
	
	返回：`#不再赘述`
	
* 文档段落添加
	
	访问：`/section/add`
	
	参数：`doc_id`, `content`
	
	返回：`#...`
	
* 文档段落修改

	访问：`/section/update`
	
	参数：`section_id`, `content`
	
	返回： *注：`code:1024`表示锁状态*
	
* 文档段落显示

	访问：`/section/show`
	
	参数：`section_id`
	
	返回：`#...`
	
* 文档段落删除（软删/强制删）

	访问：`/section/soft_del`
	
	参数：`section_id`
	
	返回：`#...`
	
* 文档段落更新

	访问：`/section/update`
	
	参数：`section_id`, `content`
	
	返回：*注: 成功时会返回 section 内容 `data: {content}`*