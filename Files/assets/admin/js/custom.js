function showAmount(amount, decimal = 4, separate = true, exceptZeros = false) {
    let separator = '';
    if (separate) {
      separator = ',';
    }
  
    amount = parseFloat(amount).toFixed(decimal).split('.');
    let printAmount = amount[0].replace(/\B(?=(\d{3})+(?!\d))/g, separator);
    printAmount = printAmount + '.' + amount[1];
  
    if (exceptZeros) {
      let exp = printAmount.split('.');
      if (Number(exp[1]) * 1 === 0) {
        printAmount = exp[0];
      } else {
        printAmount = printAmount.replace(/(\.[0-9]*[1-9])0+$/, '$1');
      }
    }
    return printAmount;
  }
  
  function getAmount(amount) {
    return parseFloat(amount).toFixed(window.allow_decimal)
  }