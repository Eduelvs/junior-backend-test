<template>
  <div class="p-6 max-w-xl mx-auto">
    <transition name="fade">
      <div
        v-if="notification"
        class="fixed top-4 right-4 bg-green-100 text-green-800 border border-green-300 rounded shadow-lg px-6 py-3 z-50"
      >
        {{ notification }}
      </div>
    </transition>

    <h1 class="text-3xl font-bold mb-6 text-center">Contatos</h1>

    <form @submit.prevent="submit" class="mb-6 flex flex-col gap-4 bg-white p-6 rounded shadow">
      <input v-model="form.name" placeholder="Nome" class="input" required />
      <input v-model="form.email" type="email" placeholder="E-mail" class="input" required />
      <input v-model="form.phone" placeholder="Telefone" class="input" required />
      <button type="submit" class="btn-blue">Adicionar</button>
    </form>

    <ul class="space-y-4">
      <li
        v-for="contact in contacts.data"
        :key="contact.id"
        class="bg-white rounded-xl shadow-md p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center hover:shadow-lg transition-shadow duration-300 border border-gray-100"
      >
        <div>
          <p class="font-bold text-lg text-gray-800">{{ contact.name }}</p>
          <p class="text-gray-500 flex items-center gap-1">
            📧 {{ contact.email }}
          </p>
          <p class="text-gray-500 flex items-center gap-1">
            📞 {{ formatPhone(contact.phone) }}
          </p>
        </div>

        <div class="flex gap-2 mt-4 sm:mt-0">
          <button @click="startEditing(contact)" class="btn-yellow flex items-center gap-1">
            ✏️ Editar
          </button>
          <button @click="destroy(contact.id)" class="btn-red flex items-center gap-1">
            🗑️ Excluir
          </button>
        </div>
      </li>
    </ul>

    <!-- Modal de edição -->
    <div v-if="editingContact" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded p-6 w-96 max-w-full">
        <h2 class="text-xl font-bold mb-4">Editar Contato</h2>

        <form @submit.prevent="submitEdit" class="flex flex-col gap-4">
          <input v-model="editForm.name" placeholder="Nome" class="input" required />
          <input v-model="editForm.email" type="email" placeholder="E-mail" class="input" required />
          <input v-model="editForm.phone" placeholder="Telefone" class="input" required />

          <div class="flex justify-end gap-2">
            <button type="button" @click="cancelEdit" class="btn-gray">Cancelar</button>
            <button type="submit" class="btn-green">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<style scoped>

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.input {
  border: 1px solid #ccc;
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
  outline: none;
  transition: 0.2s;
}
.input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 1px #3b82f6;
}
.btn-blue {
  background-color: #3b82f6;
  color: white;
  padding: 0.5rem;
  border-radius: 0.375rem;
  font-weight: 600;
}
.btn-blue:hover {
  background-color: #2563eb;
}
.btn-yellow {
  background-color: #facc15;
  color: black;
  padding: 0.5rem;
  border-radius: 0.375rem;
  font-weight: 600;
}
.btn-red {
  background-color: #ef4444;
  color: white;
  padding: 0.5rem;
  border-radius: 0.375rem;
  font-weight: 600;
}
.btn-red:hover {
  background-color: #dc2626;
}
.btn-green {
  background-color: #10b981;
  color: white;
  padding: 0.5rem;
  border-radius: 0.375rem;
  font-weight: 600;
}
.btn-gray {
  background-color: #e5e7eb;
  color: black;
  padding: 0.5rem;
  border-radius: 0.375rem;
  font-weight: 600;
}
</style>

<script setup>
import { reactive, ref } from 'vue'
import { defineProps } from 'vue'
import { Inertia } from '@inertiajs/inertia'


const props = defineProps({
  contacts: Object,
  notification: String,
})

const notification = ref(null)

const showNotification = (message) => {
  notification.value = message
  setTimeout(() => {
    notification.value = null
  }, 5000)
}


// Importa o watch do Vue
import { watch } from 'vue'

const form = reactive({
  name: '',
  email: '',
  phone: '',
})

const editingContact = ref(false)
const editForm = reactive({
  id: null,
  name: '',
  email: '',
  phone: '',
})

const submit = () => {
  Inertia.post('/contacts', { ...form }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      form.name = ''
      form.email = ''
      form.phone = ''
      showNotification('Contato adicionado com sucesso!')
    },
    onError: (errors) => {
      console.error('Erro ao criar contato:', errors)
    }
  })
}

const startEditing = (contact) => {
  editingContact.value = true
  editForm.id = contact.id
  editForm.name = contact.name
  editForm.email = contact.email
  editForm.phone = contact.phone
}

const cancelEdit = () => {
  editingContact.value = false
  editForm.id = null
  editForm.name = ''
  editForm.email = ''
  editForm.phone = ''
}

const submitEdit = () => {
  Inertia.post(`/contacts/${editForm.id}`, {
    ...editForm,
    _method: 'PUT' // ou 'PATCH'
  }, {
    onSuccess: () => {
      cancelEdit()
      showNotification('Contato atualizado com sucesso!')
    }
  })
}



const destroy = (id) => {
  if (confirm('Tem certeza que deseja excluir?')) {
    Inertia.post(`/contacts/${id}`, {
      _method: 'DELETE'
    }, {
      onSuccess: () => {
        showNotification('Contato excluído com sucesso!')
      },
      onError: (errors) => {
        console.error('Erro ao excluir contato:', errors)
      }
    })
  }
}

  function formatPhone(phone) {
    if (!phone) return '';
    const cleaned = phone.replace(/\D/g, '');
    if (cleaned.length === 11) {
      return `(${cleaned.slice(0,2)}) ${cleaned.slice(2,7)}-${cleaned.slice(7)}`;
    } else if (cleaned.length === 10) {
      return `(${cleaned.slice(0,2)}) ${cleaned.slice(2,6)}-${cleaned.slice(6)}`;
    }
    return phone;
  }

</script>

