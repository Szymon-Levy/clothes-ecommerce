const InputValidator = function() {}

InputValidator.prototype.emailValidation = function(email, isRequired = false) {
  const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

  if (isRequired && email === '') {
    return 'E-mail address is required!'
  }
  else if (!email.match(regex)) {
    return 'E-mail address format is invalid!'
  }
  else if (email.length > 255) {
    return 'E-mail address cannot be longer than 255 characters!'
  }

  return false
}

InputValidator.prototype.lengthValidation = function(value, name, from, to, isRequired = false) {
  if (isRequired && value === '') {
    return `${name} is required!`
  }
  else if (value !== '' && (value.length < from || value.length > to)) {
    return  `${name} cannot be shorter than ${from} and longer than ${to} characters!`
  }

  return false
}

InputValidator.prototype.multiValuesValidation = function(value, name, checkValuesSet, isRequired = false) {
  if (isRequired && value === '') {
    return `${name} is required!`
  }
  else if (!checkValuesSet.includes(value) && !(value == '')) {
    return  `${name} value is incorrect!`
  }

  return false
}

export { InputValidator }