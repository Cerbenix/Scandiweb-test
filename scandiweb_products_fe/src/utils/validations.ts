export const validateSku = (sku: string, skuList: string[]) => {
  if ((sku && (sku.trim().length < 8 || sku.trim().length > 12)) || !sku) {
    return 'SKU must be between 8 and 12 characters long'
  } else if (sku && skuList.includes(sku.trim())) {
    return 'SKU must be unique'
  }
  return ''
}

export const validateName = (name: string) => {
  if ((name && name.trim().length < 1) || !name) {
    return 'Product name is required'
  }
  return ''
}

export const validatePrice = (price: string) => {
  if ((price && isNaN(Number(price))) || !price) {
    return 'Product price is required'
  }
  return ''
}

export const validateType = (type: string) => {
  if (!type) {
    return 'Product type is required'
  }
  return ''
}

export const validateSize = (size: string) => {
  if (size && isNaN(Number(size))) {
    return 'Product size must be a number'
  }
  if (size === '') {
    return 'Product size is required'
  }
  return ''
}

export const validateWeight = (weight: string) => {
  if (weight && isNaN(Number(weight))) {
    return 'Product weight must be a number'
  }
  if (weight === '') {
    return 'Product weight is required'
  }
  return ''
}

export const validateDimensions = (width: string, length: string, height: string) => {
  if (
    (width && isNaN(Number(width))) ||
    (length && isNaN(Number(length))) ||
    (height && isNaN(Number(height)))
  ) {
    return 'Product dimensions must be only numbers'
  }
  if (width === '' || length === '' || height === '') {
    return 'Product dimensions are required'
  }
  return ''
}
