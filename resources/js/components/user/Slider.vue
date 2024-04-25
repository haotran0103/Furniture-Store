<template>
  <section class="slider_section">
    <div id="customCarousel1" class="carousel slide" data-ride="carousel" data-interval="5000">
      <div class="carousel-inner">
        <div
          class="carousel-item"
          v-for="(slider, index) in sliders"
          :key="slider.id"
          :class="{ active: index === 0 }"
        >
          <div class="img-box">
            <img :src="slider.image" alt="">
            <div class="carousel-caption">
              <div class="container">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-box">
                      <h1 class="text-white">
                        {{ slider.name }}
                      </h1>
                      <p class="text-white">
                        {{ slider.description }}
                      </p>
                      <a href="#" class="text-white">
                        Đọc thêm
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#customCarousel1" role="button" data-slide="prev">
        <i class="fa fa-angle-left" aria-hidden="true"></i>
        <span class="sr-only">Trước</span>
      </a>
      <a class="carousel-control-next" href="#customCarousel1" role="button" data-slide="next">
        <i class="fa fa-angle-right" aria-hidden="true"></i>
        <span class="sr-only">Tiếp theo</span>
      </a>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const sliders = ref([]);

onMounted(async () => {
  try {
    const response = await axios.get('/api/sliders');
    console.log(response.data.data.data)
    sliders.value = response.data.data.data;
  } catch (error) {
    console.error('Error fetching sliders:', error);
  }
});
</script>

<style scoped>
.img-box {
  position: relative;
}

.carousel-caption {
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  transform: translateY(-50%);
}

.carousel-caption * {
  color: white;
}
</style>
