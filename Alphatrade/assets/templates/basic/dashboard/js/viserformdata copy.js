
const viserformdata = {}

viserformdata.createFormGroup = (data) => {
    const formGroup = document.createElement('div');
    const label     = document.createElement('label');

    formGroup.classList.add('form-group');
    label.classList.add('form-label');
    label.textContent = data.name;
    formGroup.appendChild(label);

    return formGroup;
}

viserformdata.createlNewElement = (inputType,data) => {
    const input = document.createElement(inputType);
    input.type = 'text';
    input.classList.add('form-control', 'form--control');
    input.name = data.label;

    if (data.is_required === 'required') input.required = true;
    const formGroup = viserformdata.createFormGroup();

    return [input, formGroup];
}

viserformdata.createInputElement = () => {
    const [input, formGroup] = this.createlNewElement('input');
    formGroup.appendChild(input);
    return formGroup;
}

viserformdata.createTextareaElement = () => {
    const [textarea, formGroup] = this.createlNewElement('textarea');
    formGroup.appendChild(textarea);
}

viserformdata.createSelectElement = (data) => {
    const [select, formGroup] = this.createlNewElement('select');
    const option = this.creatNewOption('Select One');
    select.appendChild(option);

    data.options.forEach(item => {
        const option = this.creatNewOption(item);
        select.appendChild(option);
    });
    formGroup.appendChild(select);
}

viserformdata.createCheckBoxElement = () => {
    data.options.forEach(option => {
        const formGroup = this.createFormGroup();
        const checkboxDiv = document.createElement('div');
        checkboxDiv.classList.add('form-check');
        const [checkbox, checkboxLabel] = this.createSingleChekcBox(data.label, option);
        checkboxDiv.appendChild(checkbox);
        checkboxDiv.appendChild(checkboxLabel);
        formGroup.appendChild(checkboxDiv);
    });
}
viserformdata.createRadioElement = () => {
    data.options.forEach(option => {
        const radioDiv = document.createElement('div');
        radioDiv.classList.add('form-check');

        const radio = document.createElement('input');
        radio.classList.add('form-check-input');
        radio.name = data.label;
        radio.type = 'radio';
        radio.value = option;
        radio.id = `${data.label}_${this.titleToKey(option)}`;
        if (option === data.label) {
            radio.checked = true;
        }

        const radioLabel = document.createElement('label');
        radioLabel.classList.add('form-check-label');
        radioLabel.htmlFor = `${data.label}_${this.titleToKey(option)}`;
        radioLabel.textContent = option;

        radioDiv.appendChild(radio);
        radioDiv.appendChild(radioLabel);

        formGroup.appendChild(radioDiv);
    });
}
viserformdata.createFileElement = () => {
    const [fileInput, formGroup] = this.createlNewElement('input');

    fileInput.accept = data.extensions.split(',').map(ext => `.${ext}`).join(', ');

    const preElement = document.createElement('pre');
    preElement.classList.add('text--base', 'mt-1');
    preElement.textContent = `Supported mimes: ${data.extensions}`;

    formGroup.appendChild(fileInput);
    formGroup.appendChild(preElement);
}

viserformdata.createCheckBoxElementcreatelNewElement(inputType) {
    const input = document.createElement(inputType);
    input.type = 'text';
    input.classList.add('form-control', 'form--control');
    input.name = this.data.label;

    if (this.data.is_required === 'required') input.required = true;
    const formGroup = this.createFormGroup();
    return [input, formGroup];
}

viserformdata.creatNewOption(text, value = "") = () => {
    const option = document.createElement('option');
    option.value = value;
    option.textContent = text;
    return option;
}
viserformdata.createSingleChekcBox(label, option) {
    const checkbox = document.createElement('input');
    checkbox.classList.add('form-check-input');
    checkbox.name = `${label}[]`;
    checkbox.type = 'checkbox';
    checkbox.value = option;
    checkbox.id = `${label}_${this.titleToKey(option)}`;

    const checkboxLabel = document.createElement('label');
    checkboxLabel.classList.add('form-check-label');
    checkboxLabel.htmlFor = `${label}_${this.titleToKey(option)}`;
    checkboxLabel.textContent = option;

    return [checkbox, checkboxLabel];
}
viserformdata.titleToKey(title) {
    return title.replace(/\s+/g, '_').toLowerCase();
}

viserformdata.elementType = {
    'text': viserformdata.createInputElement,
    'textarea': viserformdata.createTextareaElement,
    'select': viserformdata.createSelectElement,
    'checkbox': viserformdata.createCheckBoxElement,
    'radio': viserformdata.createRadioElement,
    'file': viserformdata.createFileElement,
}

viserformdata.generatHtml = (formData) => {
    const fragment = document.createDocumentFragment();
    for (const key in formData) {
        const data = formData[key];
    }
}