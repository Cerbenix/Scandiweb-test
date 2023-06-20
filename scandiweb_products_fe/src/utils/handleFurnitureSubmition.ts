import {validateSku, validateName, validatePrice, validateDimensions, validateType} from '@/utils/validations'
import type { ValidationErrors } from '@/views/CreateProductView.vue'
import { ref } from 'vue'
import type { Furniture } from '../views/ProductView.vue'

export const handleFurnitureSubmition = (values: Record<string, string>, skuList: string[]) => {
    const errors = ref<ValidationErrors>({})

    errors.value.sku = validateSku(values.sku, skuList)
    errors.value.name = validateName(values.name)
    errors.value.price = validatePrice(values.price)
    errors.value.type = validateType(values.type)
    errors.value.dimensions = validateDimensions(values.width, values.length, values.height)

    if (Object.values(errors.value).some(error => error !== '')) {
        return { errors: errors.value, product: null}
    }

    const furniture: Partial<Furniture> = {
        sku: values.sku,
        name: values.name,
        price: Number(values.price),
        type: values.type,
        dimensions: values.width + 'x' + values.length + 'x' + values.height
      };

    return { errors: null, product: furniture}
}