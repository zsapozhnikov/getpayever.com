Vagrant.configure("2") do |config|
  config.vm.box = "debian/jessie64"
  config.vm.box_version = "8.5.2"
  config.vm.network :forwarded_port, host: 8080, guest: 80
end
