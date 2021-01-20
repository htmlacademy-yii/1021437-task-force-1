const autoCompleteJS = new autoComplete({
  name: "town",
  data: {
    src: async function () {
      const query = document.querySelector("#autoComplete").value;
      const source = await fetch(`/geocoder/coordinates?&query=${query}`);
      const data = await source.json();
      const response = data.response.GeoObjectCollection;
      coordinationList = [];
      // city = [];
      var list = [];
      $.each(response.featureMember, function(key, val) {
        list.push(val.GeoObject.metaDataProperty.GeocoderMetaData.text);
        coordinationList.push(val.GeoObject.Point.pos);
        // if (val.GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.AdministrativeArea.hasOwnProperty('SubAdministrativeArea')) {
        //   debugger
        //   city.push(val.GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.LocalityName);
        // }
      });
      return list;
    },
  },
  placeHolder: "Санкт-Петербург, Калининский район",
  searchEngine: "strict",
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
    document.querySelector("#autoComplete").value = feedback.selection.value;
    document.querySelector("#coordinates").value = coordinationList[feedback.selection.index];
  },
});
