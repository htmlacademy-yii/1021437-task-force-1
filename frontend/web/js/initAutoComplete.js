const autoCompleteJS = new autoComplete({
  name: "town",
  data: {
    src: async function () {
      const query = document.querySelector("#autoComplete").value;
      const source = await fetch(`/geocoder/coordinates?&query=${query}`);
      return await source.json();
    },
    key: ['text'],
    cache: false
  },
  placeHolder: "Санкт-Петербург, Калининский район",
  searchEngine: "strict",
  selector: "#autoComplete",
  highlight: true,
  maxResults: 10,
  debounce: 300,
  threshold: 2,
  resultItem: {
    content: (data, element) => {
      element.style = "display: flex; justify-content: space-between;";
      element.innerHTML = `<span style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                        ${element.innerHTML}</span>`;
    },
  },
  resultsList: {
    container: source => {
      source.setAttribute("id", "cities_list");
    },
    destination: "#autoComplete",
    position: "afterend",
    element: "ul"
  },
  noResults: (dataFeedback, generateList) => {
    generateList(autoCompleteJS, dataFeedback, dataFeedback.results);
    const result = document.createElement("li");
    result.setAttribute("class", "no_result");
    result.setAttribute("tabindex", "1");
    result.innerHTML = `<span style="display: flex; align-items: center; font-weight: 100; color: rgba(0,0,0,.2);">Нет результата по поиску: "${dataFeedback.query}"</span>`;
    document.querySelector("#cities_list").appendChild(result);
  },
  onSelection: (feedback) => {
    document.querySelector("#autoComplete").blur();
    document.querySelector("#autoComplete").value = feedback.selection.value.text;
    document.querySelector("#latitude").value = feedback.selection.value.latitude;
    document.querySelector("#longitude").value = feedback.selection.value.longitude;
    document.querySelector("#cityId").value = feedback.selection.value.city;
  },
});
