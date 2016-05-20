class HtmlTableViewer {
  view(array) {
    if (array.length === 0) return "<b>Nothing to display</b>";
    const headers = Object.keys(array[0]);
    const mapjoin = (a, f) => a.map(f).join('');
    headers.map(h => `<th>${h}</th>`)
    return "<table><thead><tr>" +
              mapjoin(headers, h => `<th>${h}</th>`) +
              "</tr></thead><tbody>" +
              mapjoin(array, e => "<tr>" + mapjoin(headers, h => `<td>${e[h]}</td>`) + "</tr>") +
              "</tbody></table>";
  }
}

class TabularData {
  constructor(viewer) {
    this.viewer = viewer;
  }
}

class Request extends TabularData {
  constructor(viewer) {
    super(viewer);
  }
  doRequest(apiname) {
    return fetch("../backend/api.php?api="+apiname)
              .then(r=>r.json())
              .then(r => {this.results = r; return r});
  }
  view() {
    return this.viewer.view(this.results);
  }
}


/**
Make a request to the API and display the result
@param {String} apiname
*/
function doreq(apiname) {
  const viewer = new HtmlTableViewer();
  const r = new Request(viewer);
  r.doRequest(apiname).then(function(){
    document.getElementById("result").innerHTML = r.view();
  })
}
