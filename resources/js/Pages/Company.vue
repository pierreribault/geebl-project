<template>
    <Head>
        <title>{{ $page.props.title }} - My awesome app</title>
    </Head>

    <div class="p-6">
        <h1>This is: {{ $page.props.title }}</h1>
    </div>

     <div class="container">

        <h1 class="text-success" >Login</h1>

        {{ models }}

    </div>
</template>

<script>
  export default {
    components: {
    },
    props: {
      models: Object,
    },
  }
</script>
