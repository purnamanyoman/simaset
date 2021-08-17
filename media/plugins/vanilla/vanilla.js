function serialize(form) {
    var field, l, s = [];
    if (typeof form == 'object' && form.nodeName == "FORM") {
        var len = form.elements.length;
        for (var i=0; i<len; i++) {
            field = form.elements[i];
            if (field.name && !field.disabled && field.type != 'file' && field.type != 'reset' && field.type != 'submit' && field.type != 'button') {
                if (field.type == 'select-multiple') {
                    l = form.elements[i].options.length; 
                    for (var j=0; j<l; j++) {
                        if(field.options[j].selected)
                            s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.options[j].value);
                    }
                } else if ((field.type != 'checkbox' && field.type != 'radio') || field.checked) {
                    s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value);
                }
            }
        }
    }
    return s.join('&').replace(/%20/g, '+');
}

function serializeArray(form) {
    var field, l, s = [];
    if (typeof form == 'object' && form.nodeName == "FORM") {
        var len = form.elements.length;
        for (var i=0; i<len; i++) {
            field = form.elements[i];
            if (field.name && !field.disabled && field.type != 'file' && field.type != 'reset' && field.type != 'submit' && field.type != 'button') {
                if (field.type == 'select-multiple') {
                    l = form.elements[i].options.length; 
                    for (j=0; j<l; j++) {
                        if(field.options[j].selected)
                            s[s.length] = { name: field.name, value: field.options[j].value };
                    }
                } else if ((field.type != 'checkbox' && field.type != 'radio') || field.checked) {
                    s[s.length] = { name: field.name, value: field.value };
                }
            }
        }
    }
    return s;
}

const $yearMask = document.getElementsByClassName('tahun');
const $dateMask = document.getElementsByClassName('tanggal');
const $timeMask = document.getElementsByClassName('waktu');
var timeMask = new Inputmask("hh:mm", {
    hourFormat: "24"
});
var dateMask = new Inputmask("dd-mm-yyyy")
var yearMask = new Inputmask("9999", {
    postValidation: function (buffer, opts) {
        return parseInt(buffer.join('')) <= (new Date()).getFullYear()
    }
})
var i;
if ($yearMask) {
    for (i = 0; i < $yearMask.length; i++) {
        yearMask.mask($yearMask)
    }
}

if ($dateMask) {
    for (i = 0; i < $dateMask.length; i++) {
        dateMask.mask($dateMask);
    }
}

if ($timeMask) {
    for (i = 0; i < $timeMask.length; i++) {
        timeMask.mask($timeMask);
    }
}