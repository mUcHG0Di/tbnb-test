import moment from "moment";

// Upper case first
Vue.filter("ucFirst", function(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});


Vue.filter("formatDate", function(date) {
    const parsedDate = moment(date);
    return parsedDate.isValid() ? parsedDate.locale("es").format("LL") : "N/A";
});

Vue.filter('onlyNumbers', function(string) {
    let value = string;
    if (value == "") return 0;
    value = value.replace(/\D/g, "");
    value = value == "" ? 0 : value;
    return parseInt(value, 10).toLocaleString();
});
