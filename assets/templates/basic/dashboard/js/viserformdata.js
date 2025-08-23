"use strict";
const viserformdata = {};

viserformdata.input_value = {}

viserformdata.classList = {
    'input': 'form-control form--control',
    'textarea': "form-control form--control",
    'select': "form-control form--control form-select",
    'checkbox': "form-check-input",
    'checkbox_label': "form-check-label",
    'radio': "form-check-input",
    'radio_label': "form-check-label",
    'file': "form-control form--control",
}
viserformdata.createPlainElement = (type, attr) => {
    const element = document.createElement(type);
    for (const key in attr) {
        const value = attr[key];
        if (!value) continue;

        if (key == 'textContent' || key == 'value') {
            element[key] = value;
            continue
        }
        element.setAttribute(key, value);
    }
    return element;
}

viserformdata.createInputElement = (data) => {
    const formGroup = viserformdata.createFormGroup(data);
    const input = viserformdata.createPlainElement('input', {
        type: 'text',
        class: viserformdata.classList.input,
        name: data.label,
        required: data.is_required === 'required',
        value: viserformdata.getValue(data.label)
    });
    formGroup.appendChild(input);
    return formGroup;
}

viserformdata.createTextareaElement = (data) => {
    const formGroup = viserformdata.createFormGroup(data);
    const textarea = viserformdata.createPlainElement('textarea', {
        class: viserformdata.classList.textarea,
        name: data.label,
        required: data.is_required === 'required',
        textContent: viserformdata.getValue(data.label)
    });
    formGroup.appendChild(textarea);
    return formGroup;
}

viserformdata.createSelectElement = (data) => {

    const formGroup = viserformdata.createFormGroup(data);
    const select = viserformdata.createPlainElement('select', {
        class: viserformdata.classList.select,
        name: data.label,
        required: data.is_required === 'required',

    });
    formGroup.appendChild(select);

    const option = viserformdata.createPlainElement('option', {
        textContent: "select One",
        selected: true,
        disabled: true,
    });
    select.appendChild(option);
    data.options.forEach(item => {
        const option = viserformdata.createPlainElement('option', {
            value: item,
            textContent: item,
            selected: viserformdata.getValue(data.label, item)
        });
        select.appendChild(option);
    });
    formGroup.appendChild(select);
    return formGroup;
}

viserformdata.createCheckBoxElement = (data) => {
    const formGroup = viserformdata.createFormGroup(data);
    data.options.forEach(option => {
        const checkboxDiv = viserformdata.createPlainElement('div', {
            class: "form-check"
        });
        const checkbox = viserformdata.createPlainElement('input', {
            class: viserformdata.classList.checkbox,
            name: `${data.label}[]`,
            type: 'checkbox',
            value: option,
            id: `${data.label}_${viserformdata.titleToKey(option)}`,
            checked: viserformdata.getValue(data.label, option)
        });

        const checkboxLabel = viserformdata.createPlainElement('label', {
            class: viserformdata.classList.checkbox_label,
            for: `${data.label}_${viserformdata.titleToKey(option)}`,
            textContent: option,
        });

        checkboxDiv.appendChild(checkbox);
        checkboxDiv.appendChild(checkboxLabel);
        formGroup.appendChild(checkboxDiv);
    });
    return formGroup;
}

viserformdata.createRadioElement = (data) => {
    const formGroup = viserformdata.createFormGroup(data);
    data.options.forEach(option => {

        const radioDiv = viserformdata.createPlainElement('div', {
            class: "form-check"
        })
        const radio = viserformdata.createPlainElement('input', {
            name: data.label,
            class: viserformdata.classList.radio,
            type: "radio",
            value: option,
            id: `${data.label}_${viserformdata.titleToKey(option)}`,
            checked: viserformdata.getValue(data.label, option)
        })
        const radioLabel = viserformdata.createPlainElement('label', {
            class: viserformdata.classList.radio_label,
            for: `${data.label}_${viserformdata.titleToKey(option)}`,
            textContent: option
        });

        radioDiv.appendChild(radio);
        radioDiv.appendChild(radioLabel);
        formGroup.appendChild(radioDiv);
    });

    return formGroup;
}

viserformdata.createFileElement = (data) => {
    const formGroup = viserformdata.createFormGroup(data);
    const fileInput = viserformdata.createPlainElement('input', {
        accept: data.extensions.split(',').map(ext => `.${ext}`).join(', '),
        class: viserformdata.classList.file,
        type: "file"
    });
    const preElement = viserformdata.createPlainElement('pre', {
        class: "text--base mt-1"
    });
    preElement.textContent = `Supported mimes: ${data.extensions}`;
    formGroup.appendChild(fileInput);
    formGroup.appendChild(preElement);
    return formGroup;
}

viserformdata.createEmailElement = (data) => {
    const formGroup = viserformdata.createFormGroup(data);
    const input = viserformdata.createPlainElement('input', {
        type: 'email',
        class: viserformdata.classList.input,
        name: data.label,
        required: data.is_required === 'required',
        value: viserformdata.getValue(data.label)
    });
    formGroup.appendChild(input);
    return formGroup;
}

viserformdata.createNumberElement = (data) => {
    const formGroup = viserformdata.createFormGroup(data);
    const input = viserformdata.createPlainElement('input', {
        type: 'number',
        class: viserformdata.classList.input,
        name: data.label,
        required: data.is_required === 'required',
        value: viserformdata.getValue(data.label)
    });
    formGroup.appendChild(input);
    return formGroup;
}
viserformdata.createUrlElement = (data) => {
    const formGroup = viserformdata.createFormGroup(data);
    const input = viserformdata.createPlainElement('input', {
        type: 'url',
        class: viserformdata.classList.input,
        name: data.label,
        required: data.is_required === 'required',
        value: viserformdata.getValue(data.label)
    });
    formGroup.appendChild(input);
    return formGroup;
}
viserformdata.createDateTimeElement = (data) => {
    const formGroup = viserformdata.createFormGroup(data);
    const input = viserformdata.createPlainElement('input', {
        type: 'datetime-local',
        class: viserformdata.classList.input,
        name: data.label,
        required: data.is_required === 'required',
        value: viserformdata.getValue(data.label)
    });
    formGroup.appendChild(input);
    return formGroup;
}
viserformdata.createDateElement = (data) => {
    const formGroup = viserformdata.createFormGroup(data);
    const input = viserformdata.createPlainElement('input', {
        type: 'date',
        class: viserformdata.classList.input,
        name: data.label,
        required: data.is_required === 'required',
        value: viserformdata.getValue(data.label)
    });
    formGroup.appendChild(input);
    return formGroup;
}
viserformdata.createTimeElement = (data) => {
    const formGroup = viserformdata.createFormGroup(data);
    const input = viserformdata.createPlainElement('input', {
        type: 'time',
        class: viserformdata.classList.input,
        name: data.label,
        required: data.is_required === 'required',
        value: viserformdata.getValue(data.label)
    });
    formGroup.appendChild(input);
    return formGroup;
}

viserformdata.generatHtml = (data) => {

    if ('object' !== typeof data) {
        console.error("Initial object data must be object but provided data is  " + typeof data);
        return false;
    }
    const formData = 'data_for_generate_html' in data ? data.data_for_generate_html : data;
    const classList = 'classList' in data ? data.classList : {};

    if ('input_value' in data && Object.keys(data.input_value).length) {
        const newObject = {};
        for (const key in data.input_value) {
            const obj = data.input_value[key];
            const keyName = viserformdata.titleToKey(obj.name);
            newObject[keyName] = obj.value;
        }
        viserformdata.input_value = newObject;
    }

    if ('old_input_value' in data && Object.keys(data.old_input_value).length) {
        viserformdata.input_value = data.old_input_value;
    }

    if (Object.keys(classList).length) {
        viserformdata.classList = {
            ...viserformdata.classList,
            ...classList
        }
    }

    const fragment = document.createDocumentFragment();
    const viserFormElement = viserformdata.elementType;

    for (const key in formData) {
        const data = formData[key];
        const type = data.type;

        if (!(type in viserFormElement)) {
            console.error(`Element type(${type}) not supported`);
            continue;
        }
        fragment.appendChild(viserFormElement[type](data));
    }
    document.querySelector("#append").innerHTML = "";
    document.querySelector("#append").appendChild(fragment);
}


viserformdata.createFormGroup = (data) => {
    const formGroup = viserformdata.createPlainElement('div', {
        class: 'form-group'
    });
    const label = viserformdata.createPlainElement('label', {
        textContent: data.name,
        class: "form-label"
    })
    formGroup.appendChild(label);
    return formGroup;
}

viserformdata.elementType = {
    'text': viserformdata.createInputElement,
    'textarea': viserformdata.createTextareaElement,
    'select': viserformdata.createSelectElement,
    'checkbox': viserformdata.createCheckBoxElement,
    'radio': viserformdata.createRadioElement,
    'file': viserformdata.createFileElement,

    'email': viserformdata.createEmailElement,
    'number': viserformdata.createNumberElement,
    'url': viserformdata.createUrlElement,
    'datetime': viserformdata.createDateTimeElement,
    'date': viserformdata.createDateElement,
    'time': viserformdata.createTimeElement,
}

viserformdata.getValue = (name, value = null) => {

    const exits = name in viserformdata.input_value;
    if (!exits) return false;

    const oldVlaue = viserformdata.input_value[name];

    if (value) {
        if ('string' != typeof oldVlaue) {
            return oldVlaue.includes(value);
        }
        return oldVlaue == value;
    } else {
        return oldVlaue;
    }
}

viserformdata.titleToKey = (title) => {
    return title.replace(/\s+/g, '_').toLowerCase();
}
viserformdata.keyToTitle = (text) => {
    if (!text) return "";
    return text.replace(/[^A-Za-z0-9 ]/g, ' ').replace(/\b\w/g, match => match.toUpperCase());
}