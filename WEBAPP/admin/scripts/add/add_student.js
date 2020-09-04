

(function () 
{
	var students = 
	{
		count:    1,

		init:     function () 
		{
			var fs = document.getElementById("student");
			fs.appendChild(this.make("br", {id: "here"}));
			var btn = fs.appendChild(this.make("input", {type: "button", value: "Add Student", style: "background-color: #090909; color:green; border-style:solid; border-width: 1px; border-color:green;"}));
			btn.onclick = (function (_self) 
			{
				return function () 
				{
					_self.add();
					return false;
				};
			})(this);
		},

		add:      function () 
		{
			var id = "p" + ++this.count;
			var br = document.getElementById("here");
			br.parentNode.insertBefore(this.make("br"), br);
			br.parentNode.insertBefore(this.make("label", {htmlFor: id}, "FullName: "), br);
			br.parentNode.insertBefore(this.make("input", {type: "text", name: "name_list[]", id: id}), br);
			var btn = br.parentNode.insertBefore(this.make("input", {type: "button", value: "remove", _id: id}), br);
			document.getElementById(id).pattern = '[a-zA-Z ]{1,}';

			btn.onclick = this.remove;
		},

		remove:   function () 
		{
			var inp = document.getElementById(this._id);
			var lbl = inp.previousSibling;
			var br  = lbl.previousSibling;
			this.parentNode.removeChild(br);
			this.parentNode.removeChild(lbl);
			this.parentNode.removeChild(inp);
			this.parentNode.removeChild(this);
			return false;
		},

		make:     function (type, attrs, content) {
			var element = document.createElement(type);
			if (attrs !== undefined) 
			{
				for (var a in attrs) 
				{
					element[a] = attrs[a];
				}
			}

			if (content !== undefined) 
			{
				element.appendChild(document.createTextNode(content));
			}
			return element;
		}
	};
	students.init();
})();

