import {validateSku, validateName, validatePrice, validateSize, validateType} from '@/utils/validations'
import type { ValidationErrors } from '@/views/CreateProductView.vue'
import { ref } from 'vue'
import type { Dvd } from '../views/ProductView.vue'

export const handleDvdSubmition = (values: Record<string, string>, skuList: string[]) => {
    const errors = ref<ValidationErrors>({})

    errors.value.sku = validateSku(values.sku, skuList)
    errors.value.name = validateName(values.name)
    errors.value.price = validatePrice(values.price)
    errors.value.type = validateType(values.type)
    errors.value.size = validateSize(values.size)

    if (Object.values(errors.value).some(error => error !== '')) {
        return { errors: errors.value, product: null}
    }

    const dvd: Partial<Dvd> = {
        sku: values.sku,
        name: values.name,
        price: Number(values.price),
        type: values.type,
        size: values.size + 'mb'
      };

    return { errors: null, product: dvd}
}