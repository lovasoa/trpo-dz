class ListViewer {
  view(model) {
    const tablename = model.apiname,
          array = model.results;
    const $button = $("<button>Создать новый</button>")
                      .on("click", evt=>model.create());
    if (array.length === 0) return $button;
    const headers = Object.keys(array[0]).filter(col => col!=tablename+"_id");
    return $("<div>").append(
      $("<ul>").append(
        $.map(array, e => this.viewElem(model, headers, e)
      ))
    ).append($button);
  }

  viewElem(model, headers, e) {
    return $("<li>").append($.map(headers, h =>
      $("<label>")
          .text(h)
          .append(this.viewInput(model, e, h))
    )).append(
      $("<button>Удалить</button>")
        .on("click", evt=>model.delete(e))
    )
  }

  viewInput(model, e, h) {
    const handleEvt = evt => model.update(e,h,evt.target.value);
    let input = $("<input>")
                .val(e[h])
                .on("input", handleEvt);
    if (h.endsWith("_id")) {
      input = $("<select>");
      input.change(handleEvt);
      const apiname = h.slice(0, -3);
      fetch(`../backend/api.php?api=${apiname}&type=list`)
        .then(r => r.json())
        .then(r => input.append(
              $.map(r, op =>
                $("<option>")
                  .val(op[h])
                  .attr("selected", op[h]==e[h])
                  .text(op[Object.keys(op).filter(x=>!x.endsWith("_id"))[0]]))
          ));
    }
    if (h === "filename") {
      let nameinput = input;
      input = $("<span>")
                  .append(nameinput)
                  .append(
                    $("<input type='file'>")
                      .on("change", evt => {
                            const file = evt.target.files[0];
                            const filename = file.name;
                            nameinput.val(filename);
                            model.update(e,h,filename,
                                          {method:"POST", "body":file});
                          })
                  )
    }
    return input;
  }
}


class TabularData {
  constructor(viewer) {
    this.viewer = viewer;
  }
}

class Request extends TabularData {
  constructor(viewer, apiname, viewEl) {
    super(viewer);
    this.apiname = apiname;
    this.viewEl = viewEl;
  }
  read() {
    return this.fetch({type:"list"})
              .then(r => {return this.results = r})
              .then(() => this.view());
  }
  update(data, column, value, options) {
    const type = "update";
    return this.fetch({
                        type,
                        data,
                        column,
                        value
                    }, options);
  }
  create() {
    this.fetch({type:"create"}).then(()=>this.read());
  }
  delete(data) {
    this.fetch({type:"delete",data}).then(()=>this.read());
  }
  fetch(params, options) {
    var qs = new URLSearchParams("");
    qs.set("api", this.apiname);
    for (var p in params)
      qs.set(p, typeof(params[p])==="string"?params[p]:JSON.stringify(params[p]));
    let fetched = fetch("../backend/api.php?" + qs, options)
                      .then(x => x.json());
    return fetched;
  }
  view(data) {
    this.viewEl.empty();
    this.viewEl.append(this.viewer.view(this));
    return data;
  }
}


class Main {
  constructor() {
    this.resEl = $("#result");
    this.tables = ["professor", "discipline", "material"];
    $("#apis").append($.map(this.tables, t=>
        $("<li>")
          .attr("id",t)
          .text(t)
          .on("click", ()=> this.do_request(t))
    ));
    this.do_request(this.tables[0]);
  }

  do_request(apiname) {
    const viewer = new ListViewer();
    this.current_request = new Request(viewer, apiname, this.resEl);
    this.current_request.read();
  }
}

var main = new Main;
