import {validateSku, validateName, validatePrice, validateWeight, validateType} from '@/utils/validations'
import type { ValidationErrors } from '@/views/CreateProductView.vue'
import { ref } from 'vue'
import type { Book } from '../views/ProductView.vue'

export const handleBookSubmition = (values: Record<string, string>, skuList: string[]) => {
    const errors = ref<ValidationErrors>({})

    errors.value.sku = validateSku(values.sku, skuList)
    errors.value.name = validateName(values.name)
    errors.value.price = validatePrice(values.price)
    errors.value.type = validateType(values.type)
    errors.value.weight = validateWeight(values.weight)

    if (Object.values(errors.value).some(error => error !== '')) {
        return { errors: errors.value, product: null}
    }

    const book: Partial<Book> = {
        sku: values.sku,
        name: values.name,
        price: Number(values.price),
        type: values.type,
        weight: values.weight + 'kg'
      };

    return { errors: null, product: book}
}