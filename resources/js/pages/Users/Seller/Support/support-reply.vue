<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({auth:Object,users:Object,tickets:Object,replies:Object});

const form = useForm({
    parent_id:props.tickets.id,
    menu : props.tickets.menu,
    recepiant:props.tickets.recepiant,
    subject:props.tickets.subject,
    text:null,
    destination:null,
    file: null
});

const submit = (id) => {
    form.destination = id
    if(form.text){
        form.post(route('supportSeller.store'))
    }
    else
    {
        errors.value.errors = 'لطفا پس از برسی موارد الزامی مجدد فرم را ارسال نمایید'
    }
};
</script>
<template>
    <div class="col-sm-12 mt-3" v-for="(ticket,index) in props.replies" :key="index" >
        <form>
        <div class="d-flex me-2" >
            <img v-if="props.tickets.userimage[0] && props.tickets.userimage[0].status == 4" style="height:40px" class="img-xs rounded-circle" :src="'/../storage/'+props.tickets.userimage[0].url" alt="User" />
            <img v-else style="height:40px" class="img-xs rounded-circle" src="/../storage/files/default-user.png" alt="User" />
            <span class="font-xs text-muted me-1">{{ticket.user.user_name}}|{{ticket.created_at}}</span>
        </div>
        <div class="mb-4 me-5">
            <p>{{ticket.text}} <a  v-if="ticket.user.id !== props.auth.user.id" :href="'#CollapseExample'+ticket.id" class="reply" data-bs-toggle="collapse" aria-expanded="false" :aria-controls="'CollapseExample'+ticket.id">پاسخ</a></p>
        </div>
        <div class="submenu text-a-r collapse " :id="'CollapseExample'+ticket.id">
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label">پاسخ:</label>
                    <textarea v-model="form.text" name="text" placeholder="اینجا تایپ کنید" class="form-control" rows="4"></textarea>
                </div>
            </div>
            <div class="card-body" >
                <label class="form-label"> آپلودفایل<span class="text-danger me-1"></span></label>
                <div class="input-upload">
                    <!-- <img src="assets/imgs/theme/upload.svg" alt="" /> -->
                    <input name="file" class="form-control" type="file" @input="form.file = $event.target.files[0]"  id="file" />
                    <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                    {{ form.progress.percentage }}%
                    </progress>
                </div>
            </div>
            <button  @click.prevent="submit(ticket.user.id)" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary me-4">ثبت</button>
        </div>
        <div class="mb-4 me-5" v-if="ticket.file">
            <p> جهت مشاهده فایل های ضمیه را دانلود نمایید.</p>
            <!-- <button @click.prevent="submitfile(ticket.file)" class="btn btn-sm btn-primary" >دانلود</button> -->
            <a :href="$page.props.ziggy.url+'/storage/'+ticket.file.url" class="btn btn-sm btn-primary" >دانلود</a>
        </div>
    </form>
    <hr>
    </div>
</template>
